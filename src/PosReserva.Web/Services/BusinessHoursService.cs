using Microsoft.EntityFrameworkCore;
using PosReserva.Web.Data;

namespace PosReserva.Web.Services;

/// <summary>Always exactly 7 rows (one per day, seeded once) — no create/delete, only edits.</summary>
public class BusinessHoursService(PosReservaDbContext db)
{
    public static readonly string[] DayNames =
        ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];

    public Task<List<BusinessHour>> GetAllAsync(CancellationToken ct = default) =>
        db.BusinessHours.AsNoTracking().OrderBy(h => h.DayOrder).ToListAsync(ct);

    public async Task UpdateAllAsync(List<BusinessHour> hours, CancellationToken ct = default)
    {
        foreach (var hour in hours)
        {
            var existing = await db.BusinessHours.FirstAsync(h => h.Id == hour.Id, ct);
            db.Entry(existing).CurrentValues.SetValues(hour);
        }
        await db.SaveChangesAsync(ct);
    }

    /// <summary>"13:00–16:00, 20:00–23:00" / "13:00–17:00" / "Cerrado".</summary>
    public static string FormatShifts(BusinessHour hour)
    {
        if (!hour.IsOpen || hour.Shift1Start is null || hour.Shift1End is null)
            return "Cerrado";

        var text = $"{hour.Shift1Start:HH:mm}–{hour.Shift1End:HH:mm}";
        if (hour.Shift2Start is not null && hour.Shift2End is not null)
            text += $", {hour.Shift2Start:HH:mm}–{hour.Shift2End:HH:mm}";
        return text;
    }
}
