using Microsoft.EntityFrameworkCore;
using PosReserva.Web.Data;

namespace PosReserva.Web.Services;

/// <summary>Single-row settings (like pos-tpv's AppSetting) — the row is seeded once at startup.</summary>
public class SiteContentService(PosReservaDbContext db)
{
    public Task<SiteContent> GetAsync(CancellationToken ct = default) =>
        db.SiteContents.AsNoTracking().FirstAsync(ct);

    public async Task UpdateAsync(SiteContent content, CancellationToken ct = default)
    {
        var existing = await db.SiteContents.FirstAsync(c => c.Id == content.Id, ct);
        db.Entry(existing).CurrentValues.SetValues(content);
        await db.SaveChangesAsync(ct);
    }
}
