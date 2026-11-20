using Microsoft.EntityFrameworkCore;
using PosReserva.Web.Data;

namespace PosReserva.Web.Services;

public record AuthResult(bool Success, AdminUser? User, string? ErrorMessage)
{
    public static AuthResult Ok(AdminUser user) => new(true, user, null);
    public static AuthResult Fail(string message) => new(false, null, message);
}

/// <summary>Same lockout policy as pos-tpv's AuthService: 5 failed attempts locks the account for 1 minute.</summary>
public class AuthService(PosReservaDbContext db, Pbkdf2PasswordHasher hasher, IConfiguration config)
{
    private const int MaxFailedAttempts = 5;
    private static readonly TimeSpan LockoutDuration = TimeSpan.FromMinutes(1);

    public async Task<AuthResult> LoginAsync(string username, string password, CancellationToken ct = default)
    {
        var user = await db.AdminUsers.FirstOrDefaultAsync(u => u.Username == username, ct);
        if (user is null)
            return AuthResult.Fail("Usuario o contraseña incorrectos.");

        if (user.LockedUntil is { } until && until > DateTime.UtcNow)
            return AuthResult.Fail("Cuenta bloqueada temporalmente por varios intentos fallidos. Inténtalo de nuevo en un minuto.");

        if (!hasher.Verify(password, user.PasswordHash))
        {
            user.FailedLoginAttempts++;
            if (user.FailedLoginAttempts >= MaxFailedAttempts)
            {
                user.LockedUntil = DateTime.UtcNow.Add(LockoutDuration);
                user.FailedLoginAttempts = 0;
            }
            await db.SaveChangesAsync(ct);
            return AuthResult.Fail("Usuario o contraseña incorrectos.");
        }

        user.FailedLoginAttempts = 0;
        user.LockedUntil = null;
        await db.SaveChangesAsync(ct);
        return AuthResult.Ok(user);
    }

    public async Task<AuthResult> RegisterAsync(string username, string password, string inviteCode, CancellationToken ct = default)
    {
        var expectedCode = config["Admin:InviteCode"];
        if (string.IsNullOrEmpty(expectedCode) || inviteCode != expectedCode)
            return AuthResult.Fail("Código de invitación incorrecto.");

        username = username.Trim();
        if (string.IsNullOrWhiteSpace(username) || string.IsNullOrWhiteSpace(password))
            return AuthResult.Fail("Indica un usuario y una contraseña.");

        if (await db.AdminUsers.AnyAsync(u => u.Username == username, ct))
            return AuthResult.Fail("Ese nombre de usuario ya existe.");

        var user = new AdminUser { Username = username, PasswordHash = hasher.Hash(password) };
        db.AdminUsers.Add(user);
        await db.SaveChangesAsync(ct);
        return AuthResult.Ok(user);
    }
}
