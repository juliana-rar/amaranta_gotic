namespace PosReserva.Web.Data;

/// <summary>Singleton settings row (mirrors pos-tpv's AppSetting pattern) — always exactly one record.
/// Backs every editable text block on the public site (Home hero/stats/features/story, Ubicación, Contacto).</summary>
public class SiteContent
{
    public int Id { get; set; }

    // Home — hero
    public string HeroEyebrow { get; set; } = string.Empty;
    public string HeroTitle { get; set; } = string.Empty;
    public string HeroSubtitle { get; set; } = string.Empty;

    // Home — trust stats bar (4 fixed slots)
    public string Stat1Number { get; set; } = string.Empty;
    public string Stat1Label { get; set; } = string.Empty;
    public string Stat2Number { get; set; } = string.Empty;
    public string Stat2Label { get; set; } = string.Empty;
    public string Stat3Number { get; set; } = string.Empty;
    public string Stat3Label { get; set; } = string.Empty;
    public string Stat4Number { get; set; } = string.Empty;
    public string Stat4Label { get; set; } = string.Empty;

    // Home — "why choose us" feature cards (3 fixed slots)
    public string Feature1Icon { get; set; } = string.Empty;
    public string Feature1Title { get; set; } = string.Empty;
    public string Feature1Description { get; set; } = string.Empty;
    public string Feature2Icon { get; set; } = string.Empty;
    public string Feature2Title { get; set; } = string.Empty;
    public string Feature2Description { get; set; } = string.Empty;
    public string Feature3Icon { get; set; } = string.Empty;
    public string Feature3Title { get; set; } = string.Empty;
    public string Feature3Description { get; set; } = string.Empty;

    // Home — menu category circles (4 fixed slots)
    public string Category1Image { get; set; } = string.Empty;
    public string Category1Title { get; set; } = string.Empty;
    public string Category1Description { get; set; } = string.Empty;
    public string Category2Image { get; set; } = string.Empty;
    public string Category2Title { get; set; } = string.Empty;
    public string Category2Description { get; set; } = string.Empty;
    public string Category3Image { get; set; } = string.Empty;
    public string Category3Title { get; set; } = string.Empty;
    public string Category3Description { get; set; } = string.Empty;
    public string Category4Image { get; set; } = string.Empty;
    public string Category4Title { get; set; } = string.Empty;
    public string Category4Description { get; set; } = string.Empty;

    // Home — story / about section
    public string StoryEyebrow { get; set; } = string.Empty;
    public string StoryTitle { get; set; } = string.Empty;
    public string StoryParagraph1 { get; set; } = string.Empty;
    public string StoryParagraph2 { get; set; } = string.Empty;
    public string StoryQuote { get; set; } = string.Empty;
    public string StoryQuoteAuthor { get; set; } = string.Empty;

    // Ubicación
    public string LocationAddress { get; set; } = string.Empty;
    public string LocationHours { get; set; } = string.Empty;

    // Contacto
    public string ContactPhone { get; set; } = string.Empty;
    public string ContactEmail { get; set; } = string.Empty;
    public string ContactInstagram { get; set; } = string.Empty;
}
