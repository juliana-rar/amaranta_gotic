using Microsoft.EntityFrameworkCore;
using PosReserva.Web.Data;

namespace PosReserva.Web.Services;

public class GalleryService(PosReservaDbContext db)
{
    public Task<List<GalleryImage>> GetAllAsync(CancellationToken ct = default) =>
        db.GalleryImages.AsNoTracking().OrderBy(g => g.DisplayOrder).ThenBy(g => g.Id).ToListAsync(ct);

    public Task<GalleryImage?> GetByIdAsync(int id, CancellationToken ct = default) =>
        db.GalleryImages.FirstOrDefaultAsync(g => g.Id == id, ct);

    public async Task<int> CreateAsync(GalleryImage image, CancellationToken ct = default)
    {
        db.GalleryImages.Add(image);
        await db.SaveChangesAsync(ct);
        return image.Id;
    }

    public async Task UpdateAsync(GalleryImage image, CancellationToken ct = default)
    {
        db.GalleryImages.Update(image);
        await db.SaveChangesAsync(ct);
    }

    public async Task DeleteAsync(int id, CancellationToken ct = default)
    {
        var image = await db.GalleryImages.FindAsync([id], ct);
        if (image is null) return;
        db.GalleryImages.Remove(image);
        await db.SaveChangesAsync(ct);
    }
}
