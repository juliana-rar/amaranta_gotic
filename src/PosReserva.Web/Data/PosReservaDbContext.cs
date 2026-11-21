using Microsoft.EntityFrameworkCore;

namespace PosReserva.Web.Data;

public class PosReservaDbContext(DbContextOptions<PosReservaDbContext> options) : DbContext(options)
{
    public DbSet<AdminUser> AdminUsers => Set<AdminUser>();
    public DbSet<Dish> Dishes => Set<Dish>();
    public DbSet<Testimonial> Testimonials => Set<Testimonial>();
    public DbSet<GalleryImage> GalleryImages => Set<GalleryImage>();
    public DbSet<BusinessHour> BusinessHours => Set<BusinessHour>();
    public DbSet<SiteContent> SiteContents => Set<SiteContent>();
    public DbSet<SiteText> SiteTexts => Set<SiteText>();

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<AdminUser>().HasIndex(u => u.Username).IsUnique();
        modelBuilder.Entity<SiteText>().HasKey(t => t.Key);
    }
}
