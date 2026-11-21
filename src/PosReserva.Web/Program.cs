using Microsoft.AspNetCore.Authentication;
using Microsoft.AspNetCore.Authentication.Cookies;
using Microsoft.EntityFrameworkCore;
using PosReserva.Web.Components;
using PosReserva.Web.Data;
using PosReserva.Web.Services;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddRazorComponents()
    .AddInteractiveServerComponents();

// Server-to-server client for pos-tpv's public reservation endpoint. The API key lives only in
// this server-side config (appsettings/user-secrets), never reaches the customer's browser.
builder.Services.AddHttpClient<ReservationApiClient>(client =>
{
    var baseUrl = builder.Configuration["PosTpv:BaseUrl"]
        ?? throw new InvalidOperationException("Missing configuration: PosTpv:BaseUrl");
    var apiKey = builder.Configuration["PosTpv:ReservaApiKey"]
        ?? throw new InvalidOperationException("Missing configuration: PosTpv:ReservaApiKey");

    client.BaseAddress = new Uri(baseUrl);
    client.DefaultRequestHeaders.Add("X-Reserva-Api-Key", apiKey);
});

// Own small database — dishes shown on Home and editable site text, plus admin accounts.
// Fully separate from pos-tpv's database; the only link between the two projects remains the
// public reservation endpoint above.
builder.Services.AddDbContext<PosReservaDbContext>(options =>
    options.UseSqlite(builder.Configuration.GetConnectionString("Default")
        ?? throw new InvalidOperationException("Missing configuration: ConnectionStrings:Default")));

builder.Services.AddScoped<Pbkdf2PasswordHasher>();
builder.Services.AddScoped<AuthService>();
builder.Services.AddScoped<DishService>();
builder.Services.AddScoped<TestimonialService>();
builder.Services.AddScoped<GalleryService>();
builder.Services.AddScoped<BusinessHoursService>();
builder.Services.AddScoped<SiteContentService>();
builder.Services.AddScoped<SiteTextService>();

// Cookie auth for the admin area only — most pages in this app are public and carry
// [AllowAnonymous]; only /admin* pages declare [Authorize].
builder.Services.AddCascadingAuthenticationState();
builder.Services.AddHttpContextAccessor();
builder.Services
    .AddAuthentication(CookieAuthenticationDefaults.AuthenticationScheme)
    .AddCookie(options =>
    {
        options.LoginPath = "/login";
        options.ExpireTimeSpan = TimeSpan.FromHours(12);
        options.SlidingExpiration = true;
    });
builder.Services.AddAuthorization();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Error", createScopeForErrors: true);
}

app.UseAuthentication();
app.UseAuthorization();
app.UseAntiforgery();

// Cookie sign-out endpoint (same pattern as pos-tpv's /account/logout).
app.MapPost("/account/logout", async (HttpContext http) =>
{
    await http.SignOutAsync(CookieAuthenticationDefaults.AuthenticationScheme);
    return Results.Redirect("/");
}).DisableAntiforgery();

// Uploaded dish images are written to wwwroot at runtime, so they need the classic static
// file middleware in addition to MapStaticAssets (which only serves build-time-known files).
app.UseStaticFiles();
app.MapStaticAssets();
app.MapRazorComponents<App>()
    .AddInteractiveServerRenderMode();

// Create the Sqlite schema and seed the single SiteContent row on first run.
using (var scope = app.Services.CreateScope())
{
    var db = scope.ServiceProvider.GetRequiredService<PosReservaDbContext>();
    var justCreated = await db.Database.EnsureCreatedAsync();
    if (!justCreated)
        await SchemaPatcher.ApplyAsync(db);

    if (!await db.SiteContents.AnyAsync())
    {
        db.SiteContents.Add(new SiteContent
        {
            HeroEyebrow = "🔥 Brasería mediterránea en el Barri Gòtic",
            HeroTitle = "Fuego, piedra y una mesa larga en el Gótico",
            HeroSubtitle = "Carnes a la brasa, arroces para compartir y copas bajo la luz baja de la Baixada de Sant Miquel. A un paso de la Catedral de Barcelona.",

            Stat1Number = "100%", Stat1Label = "Cocina a la brasa",
            Stat2Number = "24 €", Stat2Label = "Menús de grupo desde",
            Stat3Number = "50", Stat3Label = "Personas en eventos",
            Stat4Number = "08002", Stat4Label = "Barri Gòtic, Barcelona",

            Feature1Icon = "🔥", Feature1Title = "La brasa manda", Feature1Description = "Carnes, pescados y verduras marcados por el fuego, con producto fresco y técnica visible.",
            Feature2Icon = "🍚", Feature2Title = "Para compartir", Feature2Description = "Arroces melosos y fideuás que se piden en el centro de la mesa y se reparten entre todos.",
            Feature3Icon = "🕯️", Feature3Title = "Gótico de noche", Feature3Description = "Piedra, luz baja y callejones estrechos a dos pasos de la Catedral y la Plaça Reial.",

            Category1Image = "/images/amaranta-brasa.jpg",
            Category1Title = "De la brasa", Category1Description = "Carrilleras, paletilla de cordero y entrecot madurado marcados por el fuego.",
            Category2Image = "/images/amaranta-mesa.jpg",
            Category2Title = "Arroces", Category2Description = "Paellas y arroces melosos de pulpo y langostinos para compartir.",
            Category3Image = "/images/amaranta-ambiente.jpg",
            Category3Title = "Tapas", Category3Description = "Gambas al ajillo, tataki de atún y patatas amarantas para ir picando.",
            Category4Image = "/images/amaranta-ambiente.jpg",
            Category4Title = "Copas", Category4Description = "Coctelería, vino de la casa y sobremesa larga en el Gótico.",

            StoryEyebrow = "Nuestra historia",
            StoryTitle = "Una callejuela, una brasa y una mesa larga",
            StoryParagraph1 = "Amaranta Gótico nació en la Baixada de Sant Miquel, entre piedras antiguas y el rumor de la Catedral. Aquí el fuego marca el ritmo: carnes a la brasa, arroces que se comparten y tapas que invitan a quedarse.",
            StoryParagraph2 = "Somos un local de barrio con alma bohemia: luz baja, paredes de piedra, copas bien servidas y sitio para todos. Venga solo, en pareja o con veinte personas: la mesa se hace grande cuando hace falta.",
            StoryQuote = "El fuego no tiene prisa, y aquí tampoco.",
            StoryQuoteAuthor = "Amaranta Gótico",

            LocationAddress = "Baixada de Sant Miquel, 6\n08002 Barcelona",
            LocationHours = "Todos los días: 11:00–16:00 y 18:45–23:00",
            ContactPhone = "+34 644 260 818",
            ContactEmail = "amarantagotico@gmail.com",
            ContactInstagram = "@amarantagotico"
        });
        await db.SaveChangesAsync();
    }
    else
    {
        // Backfill only the fields added by this schema patch on a pre-existing row, without
        // touching HeroTitle/HeroSubtitle/Location/Contact the owner may have already edited.
        var existing = await db.SiteContents.FirstAsync();
        if (string.IsNullOrEmpty(existing.HeroEyebrow))
        {
            existing.HeroEyebrow = "🔥 Brasería mediterránea en el Barri Gòtic";
            existing.Stat1Number = "100%"; existing.Stat1Label = "Cocina a la brasa";
            existing.Stat2Number = "24 €"; existing.Stat2Label = "Menús de grupo desde";
            existing.Stat3Number = "50"; existing.Stat3Label = "Personas en eventos";
            existing.Stat4Number = "08002"; existing.Stat4Label = "Barri Gòtic, Barcelona";
            existing.Feature1Icon = "🔥"; existing.Feature1Title = "La brasa manda"; existing.Feature1Description = "Carnes, pescados y verduras marcados por el fuego, con producto fresco y técnica visible.";
            existing.Feature2Icon = "🍚"; existing.Feature2Title = "Para compartir"; existing.Feature2Description = "Arroces melosos y fideuás que se piden en el centro de la mesa y se reparten entre todos.";
            existing.Feature3Icon = "🕯️"; existing.Feature3Title = "Gótico de noche"; existing.Feature3Description = "Piedra, luz baja y callejones estrechos a dos pasos de la Catedral y la Plaça Reial.";
            existing.StoryEyebrow = "Nuestra historia";
            existing.StoryTitle = "Una callejuela, una brasa y una mesa larga";
            existing.StoryParagraph1 = "Amaranta Gótico nació en la Baixada de Sant Miquel, entre piedras antiguas y el rumor de la Catedral. Aquí el fuego marca el ritmo: carnes a la brasa, arroces que se comparten y tapas que invitan a quedarse.";
            existing.StoryParagraph2 = "Somos un local de barrio con alma bohemia: luz baja, paredes de piedra, copas bien servidas y sitio para todos. Venga solo, en pareja o con veinte personas: la mesa se hace grande cuando hace falta.";
            existing.StoryQuote = "El fuego no tiene prisa, y aquí tampoco.";
            existing.StoryQuoteAuthor = "Amaranta Gótico";
            await db.SaveChangesAsync();
        }

        // Separate, independent backfill for the menu-category fields added in a later schema
        // patch than the one above — checked on its own so it still fires even though HeroEyebrow
        // is already set on a row that predates only this addition.
        if (string.IsNullOrEmpty(existing.Category1Title))
        {
            existing.Category1Image = "/images/amaranta-brasa.jpg";
            existing.Category1Title = "De la brasa"; existing.Category1Description = "Carrilleras, paletilla de cordero y entrecot madurado marcados por el fuego.";
            existing.Category2Image = "/images/amaranta-mesa.jpg";
            existing.Category2Title = "Arroces"; existing.Category2Description = "Paellas y arroces melosos de pulpo y langostinos para compartir.";
            existing.Category3Image = "/images/amaranta-ambiente.jpg";
            existing.Category3Title = "Tapas"; existing.Category3Description = "Gambas al ajillo, tataki de atún y patatas amarantas para ir picando.";
            existing.Category4Image = "/images/amaranta-ambiente.jpg";
            existing.Category4Title = "Copas"; existing.Category4Description = "Coctelería, vino de la casa y sobremesa larga en el Gótico.";
            await db.SaveChangesAsync();
        }
    }

    var existingTextKeys = await db.SiteTexts.Select(t => t.Key).ToListAsync();
    foreach (var definition in SiteTextCatalog.Definitions)
    {
        if (existingTextKeys.Contains(definition.Key, StringComparer.OrdinalIgnoreCase)) continue;
        db.SiteTexts.Add(new SiteText { Key = definition.Key, Value = definition.Value });
    }
    await db.SaveChangesAsync();

    if (!await db.BusinessHours.AnyAsync())
    {
        db.BusinessHours.AddRange(
            Enumerable.Range(0, 7).Select(day => new BusinessHour
            {
                DayOrder = day,
                IsOpen = true,
                Shift1Start = new TimeOnly(11, 0),
                Shift1End = new TimeOnly(16, 0),
                Shift2Start = new TimeOnly(18, 45),
                Shift2End = new TimeOnly(23, 0)
            })
        );
        await db.SaveChangesAsync();
    }

    if (!await db.Testimonials.AnyAsync())
    {
        db.Testimonials.AddRange(
            new Testimonial { AuthorName = "Mesa de doce", Quote = "Juntaron las mesas en un rincón precioso del Gótico. Los arroces y la paletilla a la brasa, espectaculares.", DisplayOrder = 1 },
            new Testimonial { AuthorName = "Equipo de oficina", Quote = "Cena de empresa en pleno centro, con música suave y servicio atento. Repetimos arroz meloso dos veces.", DisplayOrder = 2 },
            new Testimonial { AuthorName = "Paseantes del barrio", Quote = "Un local con carácter, luz baja y olor a brasa nada más entrar. Justo lo que buscábamos tras pasear por la Catedral.", DisplayOrder = 3 }
        );
        await db.SaveChangesAsync();
    }

    if (!await db.Dishes.AnyAsync())
    {
        db.Dishes.AddRange(
            new Dish { Title = "Carrilleras al vino tinto", Description = "Cocidas a fuego lento con vino tinto y especias.", Category = "De la brasa", PriceText = "18,50 €", DisplayOrder = 1 },
            new Dish { Title = "Paletilla de cordero a la brasa", Description = "Cordero marcado por el fuego, tierno y jugoso.", Category = "De la brasa", PriceText = "24 €", ShowOnHome = true, ImageUrl = "/images/amaranta-brasa.jpg", DisplayOrder = 2 },
            new Dish { Title = "Entrecot madurado", Description = "Entrecot madurado a la brasa con sal y romero.", Category = "De la brasa", PriceText = "26 €", DisplayOrder = 3 },
            new Dish { Title = "Lubina a la mediterránea", Description = "Lubina a la brasa con aceite, limón y hierbas.", Category = "De la brasa", PriceText = "22 €", DisplayOrder = 4 },
            new Dish { Title = "Paella de marisco", Description = "Arroz dorado con gambas, mejillones y calamar.", Category = "Arroces para compartir (mínimo 2 personas)", PriceText = "21 €", DisplayOrder = 5 },
            new Dish { Title = "Arroz meloso de pulpo y langostinos", Description = "Arroz cremoso con pulpo tierno y langostinos.", Category = "Arroces para compartir (mínimo 2 personas)", PriceText = "24 €", ShowOnHome = true, ImageUrl = "/images/amaranta-mesa.jpg", DisplayOrder = 6 },
            new Dish { Title = "Fideuà de marisco", Description = "Fideos tostados con marisco y alioli.", Category = "Arroces para compartir (mínimo 2 personas)", PriceText = "22 €", DisplayOrder = 7 },
            new Dish { Title = "Gambas al ajillo", Description = "Gambas salteadas con ajo, guindilla y aceite.", Category = "Para empezar", PriceText = "12 €", DisplayOrder = 8 },
            new Dish { Title = "Mejillones al vino blanco", Description = "Mejillones abiertos al vapor de vino blanco.", Category = "Para empezar", PriceText = "11 €", DisplayOrder = 9 },
            new Dish { Title = "Tataki de atún rojo", Description = "Atún rojo marcado con sésamo y salsa de soja.", Category = "Para empezar", PriceText = "18,50 €", ShowOnHome = true, ImageUrl = "/images/amaranta-ambiente.jpg", DisplayOrder = 10 },
            new Dish { Title = "Carpaccio de ternera", Description = "Láminas finas de ternera con aceite y parmesano.", Category = "Para empezar", PriceText = "15 €", DisplayOrder = 11 },
            new Dish { Title = "Patatas amarantas", Description = "Patatas asadas con pimentón y alioli suave.", Category = "Para empezar", PriceText = "9,50 €", DisplayOrder = 12 },
            new Dish { Title = "Tiramisú", Description = "Clásico tiramisú casero con mascarpone.", Category = "Postres y copas", PriceText = "7 €", DisplayOrder = 13 },
            new Dish { Title = "Tarta de la casa", Description = "Tarta casera del día, pregúntanos por ella.", Category = "Postres y copas", PriceText = "7 €", DisplayOrder = 14 },
            new Dish { Title = "Mojito", Description = "Ron, menta, lima y soda bien fresquitos.", Category = "Postres y copas", PriceText = "10 €", DisplayOrder = 15 },
            new Dish { Title = "Copa de vino tinto", Description = "Vino tinto de la casa, copa de 175 ml.", Category = "Postres y copas", PriceText = "6 €", DisplayOrder = 16 },
            new Dish { Title = "Caña", Description = "Caña bien tirada, la compañera de toda sobremesa.", Category = "Postres y copas", PriceText = "3,50 €", DisplayOrder = 17 }
        );
        await db.SaveChangesAsync();
    }
}

app.Run();
