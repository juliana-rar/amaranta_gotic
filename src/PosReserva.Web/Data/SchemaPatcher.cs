using Microsoft.EntityFrameworkCore;

namespace PosReserva.Web.Data;

/// <summary>
/// This project has no formal EF migrations (EnsureCreatedAsync only creates a brand-new
/// database — it never diffs an existing one). When the model grows, an existing dev database
/// needs its schema patched in place instead of being dropped, since it can already hold a real
/// admin account and content the owner edited. Idempotent: safe to run on every startup.
/// </summary>
public static class SchemaPatcher
{
    private static readonly (string Column, string SqlType)[] NewSiteContentColumns =
    [
        ("HeroEyebrow", "TEXT"),
        ("Stat1Number", "TEXT"), ("Stat1Label", "TEXT"),
        ("Stat2Number", "TEXT"), ("Stat2Label", "TEXT"),
        ("Stat3Number", "TEXT"), ("Stat3Label", "TEXT"),
        ("Stat4Number", "TEXT"), ("Stat4Label", "TEXT"),
        ("Feature1Icon", "TEXT"), ("Feature1Title", "TEXT"), ("Feature1Description", "TEXT"),
        ("Feature2Icon", "TEXT"), ("Feature2Title", "TEXT"), ("Feature2Description", "TEXT"),
        ("Feature3Icon", "TEXT"), ("Feature3Title", "TEXT"), ("Feature3Description", "TEXT"),
        ("Category1Image", "TEXT"), ("Category1Title", "TEXT"), ("Category1Description", "TEXT"),
        ("Category2Image", "TEXT"), ("Category2Title", "TEXT"), ("Category2Description", "TEXT"),
        ("Category3Image", "TEXT"), ("Category3Title", "TEXT"), ("Category3Description", "TEXT"),
        ("Category4Image", "TEXT"), ("Category4Title", "TEXT"), ("Category4Description", "TEXT"),
        ("StoryEyebrow", "TEXT"), ("StoryTitle", "TEXT"),
        ("StoryParagraph1", "TEXT"), ("StoryParagraph2", "TEXT"),
        ("StoryQuote", "TEXT"), ("StoryQuoteAuthor", "TEXT"),
    ];

    public static async Task ApplyAsync(PosReservaDbContext db)
    {
        var existingColumns = (await db.Database
                .SqlQueryRaw<string>("SELECT name FROM pragma_table_info('SiteContents')")
                .ToListAsync())
            .ToHashSet(StringComparer.OrdinalIgnoreCase);

        foreach (var (column, sqlType) in NewSiteContentColumns)
        {
            if (!existingColumns.Contains(column))
            {
                // SQL identifiers (column/type) can't be parameterized like values — column and
                // sqlType only ever come from the private NewSiteContentColumns array above, never
                // from user input, so string interpolation here is safe.
#pragma warning disable EF1002
                await db.Database.ExecuteSqlRawAsync(
                    $"ALTER TABLE SiteContents ADD COLUMN {column} {sqlType} NOT NULL DEFAULT ''");
#pragma warning restore EF1002
            }
        }

        await db.Database.ExecuteSqlRawAsync("""
            CREATE TABLE IF NOT EXISTS Testimonials (
                Id INTEGER NOT NULL CONSTRAINT PK_Testimonials PRIMARY KEY AUTOINCREMENT,
                AuthorName TEXT NOT NULL,
                Quote TEXT NOT NULL,
                DisplayOrder INTEGER NOT NULL
            )
            """);

        await db.Database.ExecuteSqlRawAsync("""
            CREATE TABLE IF NOT EXISTS GalleryImages (
                Id INTEGER NOT NULL CONSTRAINT PK_GalleryImages PRIMARY KEY AUTOINCREMENT,
                ImageUrl TEXT NOT NULL,
                Caption TEXT NOT NULL,
                DisplayOrder INTEGER NOT NULL
            )
            """);

        await db.Database.ExecuteSqlRawAsync("""
            CREATE TABLE IF NOT EXISTS BusinessHours (
                Id INTEGER NOT NULL CONSTRAINT PK_BusinessHours PRIMARY KEY AUTOINCREMENT,
                DayOrder INTEGER NOT NULL,
                IsOpen INTEGER NOT NULL,
                Shift1Start TEXT NULL,
                Shift1End TEXT NULL,
                Shift2Start TEXT NULL,
                Shift2End TEXT NULL
            )
            """);
    }
}
