using Microsoft.EntityFrameworkCore;
using PosReserva.Web.Data;

namespace PosReserva.Web.Services;

public class TestimonialService(PosReservaDbContext db)
{
    public Task<List<Testimonial>> GetAllAsync(CancellationToken ct = default) =>
        db.Testimonials.AsNoTracking().OrderBy(t => t.DisplayOrder).ThenBy(t => t.Id).ToListAsync(ct);

    public Task<Testimonial?> GetByIdAsync(int id, CancellationToken ct = default) =>
        db.Testimonials.FirstOrDefaultAsync(t => t.Id == id, ct);

    public async Task<int> CreateAsync(Testimonial testimonial, CancellationToken ct = default)
    {
        db.Testimonials.Add(testimonial);
        await db.SaveChangesAsync(ct);
        return testimonial.Id;
    }

    public async Task UpdateAsync(Testimonial testimonial, CancellationToken ct = default)
    {
        db.Testimonials.Update(testimonial);
        await db.SaveChangesAsync(ct);
    }

    public async Task DeleteAsync(int id, CancellationToken ct = default)
    {
        var testimonial = await db.Testimonials.FindAsync([id], ct);
        if (testimonial is null) return;
        db.Testimonials.Remove(testimonial);
        await db.SaveChangesAsync(ct);
    }
}
