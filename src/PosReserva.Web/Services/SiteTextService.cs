using Microsoft.EntityFrameworkCore;
using PosReserva.Web.Data;

namespace PosReserva.Web.Services;

public class SiteTextService(PosReservaDbContext db)
{
    public async Task<Dictionary<string, string>> GetAsync(CancellationToken ct = default)
    {
        var values = new Dictionary<string, string>(StringComparer.OrdinalIgnoreCase);
        foreach (var definition in SiteTextCatalog.Definitions)
            values[definition.Key] = definition.Value;

        var stored = await db.SiteTexts.AsNoTracking().ToListAsync(ct);
        foreach (var text in stored)
            values[text.Key] = text.Value;

        return values;
    }

    public async Task SaveAsync(Dictionary<string, string> values, CancellationToken ct = default)
    {
        var definitions = SiteTextCatalog.Definitions
            .ToDictionary(d => d.Key, StringComparer.OrdinalIgnoreCase);

        var canonicalKeys = values.Keys
            .Where(definitions.ContainsKey)
            .Select(key => definitions[key].Key)
            .ToHashSet(StringComparer.OrdinalIgnoreCase);

        var existing = await db.SiteTexts
            .Where(t => canonicalKeys.Contains(t.Key))
            .ToDictionaryAsync(t => t.Key, t => t, StringComparer.OrdinalIgnoreCase, ct);

        var processed = new HashSet<string>(StringComparer.OrdinalIgnoreCase);
        foreach (var (key, value) in values)
        {
            if (!definitions.TryGetValue(key, out var definition)) continue;
            if (!processed.Add(definition.Key)) continue;

            if (existing.TryGetValue(definition.Key, out var text))
                text.Value = value;
            else
                db.SiteTexts.Add(new SiteText { Key = definition.Key, Value = value });
        }

        await db.SaveChangesAsync(ct);
    }
}
