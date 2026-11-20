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
            HeroEyebrow = "🍕 Cocina italiana auténtica desde 2015",
            HeroTitle = "Pizza de horno de leña, hecha como siempre debió hacerse",
            HeroSubtitle = "Ingredientes frescos, masa de fermentación lenta y una terraza con vistas al puerto. Reserva tu mesa online en un minuto.",

            Stat1Number = "15+", Stat1Label = "Años de experiencia",
            Stat2Number = "4.8★", Stat2Label = "Valoración media",
            Stat3Number = "50k+", Stat3Label = "Pizzas servidas",
            Stat4Number = "100%", Stat4Label = "Ingredientes frescos",

            Feature1Icon = "🔥", Feature1Title = "Horno de leña", Feature1Description = "Cocción tradicional a más de 400°C, como en Nápoles.",
            Feature2Icon = "🌿", Feature2Title = "Ingredientes de km 0", Feature2Description = "Producto fresco de mercado local, sin congelados.",
            Feature3Icon = "🌅", Feature3Title = "Terraza frente al mar", Feature3Description = "Vistas al puerto, ideal para comer o cenar en cualquier época del año.",

            Category1Title = "Entrantes", Category1Description = "Bruschettas, burrata y croquetas caseras para abrir boca.",
            Category2Title = "Pizzas", Category2Description = "Masa de fermentación lenta y horno de leña, como en Nápoles.",
            Category3Title = "Postres", Category3Description = "Tiramisú y panna cotta hechos en casa cada día.",
            Category4Title = "Bebidas", Category4Description = "Vino de la casa, refrescos y agua bien fría.",

            StoryEyebrow = "Nuestra historia",
            StoryTitle = "Tradición familiar frente al mar",
            StoryParagraph1 = "Desde 2015, la familia Rossi elabora cada pizza siguiendo la receta que Nonna Lucia trajo de Nápoles: masa de fermentación lenta de 48 horas, horno de leña centenario y los mejores productos de mercado local.",
            StoryParagraph2 = "Hoy seguimos el mismo compromiso: cocina honesta, trato cercano y una terraza donde el tiempo se detiene frente al puerto.",
            StoryQuote = "No servimos comida rápida, servimos memoria napolitana.",
            StoryQuoteAuthor = "Familia Rossi",

            LocationAddress = "Paseo del Puerto, 12\nCiudad, CP 00000",
            LocationHours = "Lunes: Cerrado\nMartes – Jueves: 13:00–16:00, 20:00–23:00\nViernes – Sábado: 13:00–16:30, 20:00–00:00\nDomingo: 13:00–17:00",
            ContactPhone = "+34 600 000 000",
            ContactEmail = "hola@pizzeriadelpuerto.com",
            ContactInstagram = "@pizzeriadelpuerto"
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
            existing.HeroEyebrow = "🍕 Cocina italiana auténtica desde 2015";
            existing.Stat1Number = "15+"; existing.Stat1Label = "Años de experiencia";
            existing.Stat2Number = "4.8★"; existing.Stat2Label = "Valoración media";
            existing.Stat3Number = "50k+"; existing.Stat3Label = "Pizzas servidas";
            existing.Stat4Number = "100%"; existing.Stat4Label = "Ingredientes frescos";
            existing.Feature1Icon = "🔥"; existing.Feature1Title = "Horno de leña"; existing.Feature1Description = "Cocción tradicional a más de 400°C, como en Nápoles.";
            existing.Feature2Icon = "🌿"; existing.Feature2Title = "Ingredientes de km 0"; existing.Feature2Description = "Producto fresco de mercado local, sin congelados.";
            existing.Feature3Icon = "🌅"; existing.Feature3Title = "Terraza frente al mar"; existing.Feature3Description = "Vistas al puerto, ideal para comer o cenar en cualquier época del año.";
            existing.StoryEyebrow = "Nuestra historia";
            existing.StoryTitle = "Tradición familiar frente al mar";
            existing.StoryParagraph1 = "Desde 2015, la familia Rossi elabora cada pizza siguiendo la receta que Nonna Lucia trajo de Nápoles: masa de fermentación lenta de 48 horas, horno de leña centenario y los mejores productos de mercado local.";
            existing.StoryParagraph2 = "Hoy seguimos el mismo compromiso: cocina honesta, trato cercano y una terraza donde el tiempo se detiene frente al puerto.";
            existing.StoryQuote = "No servimos comida rápida, servimos memoria napolitana.";
            existing.StoryQuoteAuthor = "Familia Rossi";
            await db.SaveChangesAsync();
        }

        // Separate, independent backfill for the menu-category fields added in a later schema
        // patch than the one above — checked on its own so it still fires even though HeroEyebrow
        // is already set on a row that predates only this addition.
        if (string.IsNullOrEmpty(existing.Category1Title))
        {
            existing.Category1Title = "Entrantes"; existing.Category1Description = "Bruschettas, burrata y croquetas caseras para abrir boca.";
            existing.Category2Title = "Pizzas"; existing.Category2Description = "Masa de fermentación lenta y horno de leña, como en Nápoles.";
            existing.Category3Title = "Postres"; existing.Category3Description = "Tiramisú y panna cotta hechos en casa cada día.";
            existing.Category4Title = "Bebidas"; existing.Category4Description = "Vino de la casa, refrescos y agua bien fría.";
            await db.SaveChangesAsync();
        }
    }

    if (!await db.BusinessHours.AnyAsync())
    {
        var lunch = (new TimeOnly(13, 0), new TimeOnly(16, 0));
        var lunchLong = (new TimeOnly(13, 0), new TimeOnly(16, 30));
        var dinner = (new TimeOnly(20, 0), new TimeOnly(23, 0));
        var dinnerLong = (new TimeOnly(20, 0), new TimeOnly(0, 0));

        db.BusinessHours.AddRange(
            new BusinessHour { DayOrder = 0, IsOpen = false }, // Lunes
            new BusinessHour { DayOrder = 1, IsOpen = true, Shift1Start = lunch.Item1, Shift1End = lunch.Item2, Shift2Start = dinner.Item1, Shift2End = dinner.Item2 }, // Martes
            new BusinessHour { DayOrder = 2, IsOpen = true, Shift1Start = lunch.Item1, Shift1End = lunch.Item2, Shift2Start = dinner.Item1, Shift2End = dinner.Item2 }, // Miércoles
            new BusinessHour { DayOrder = 3, IsOpen = true, Shift1Start = lunch.Item1, Shift1End = lunch.Item2, Shift2Start = dinner.Item1, Shift2End = dinner.Item2 }, // Jueves
            new BusinessHour { DayOrder = 4, IsOpen = true, Shift1Start = lunchLong.Item1, Shift1End = lunchLong.Item2, Shift2Start = dinnerLong.Item1, Shift2End = dinnerLong.Item2 }, // Viernes
            new BusinessHour { DayOrder = 5, IsOpen = true, Shift1Start = lunchLong.Item1, Shift1End = lunchLong.Item2, Shift2Start = dinnerLong.Item1, Shift2End = dinnerLong.Item2 }, // Sábado
            new BusinessHour { DayOrder = 6, IsOpen = true, Shift1Start = new TimeOnly(13, 0), Shift1End = new TimeOnly(17, 0) } // Domingo
        );
        await db.SaveChangesAsync();
    }

    if (!await db.Testimonials.AnyAsync())
    {
        db.Testimonials.AddRange(
            new Testimonial { AuthorName = "María G.", Quote = "La mejor pizza napolitana que he probado fuera de Italia. El ambiente en la terraza es único.", DisplayOrder = 1 },
            new Testimonial { AuthorName = "Javier R.", Quote = "Reservamos para una cena de empresa y todo perfecto: puntualidad, trato y una carta espectacular.", DisplayOrder = 2 },
            new Testimonial { AuthorName = "Lucía M.", Quote = "Se nota el mimo en cada plato. Volvemos siempre que podemos, ¡y las vistas al puerto son un regalo!", DisplayOrder = 3 }
        );
        await db.SaveChangesAsync();
    }
}

app.Run();
