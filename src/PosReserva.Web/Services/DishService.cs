using Microsoft.EntityFrameworkCore;
using PosReserva.Web.Data;

namespace PosReserva.Web.Services;

public class DishService(PosReservaDbContext db)
{
    public Task<List<Dish>> GetAllAsync(CancellationToken ct = default) =>
        db.Dishes.AsNoTracking().OrderBy(d => d.DisplayOrder).ThenBy(d => d.Id).ToListAsync(ct);

    public Task<Dish?> GetByIdAsync(int id, CancellationToken ct = default) =>
        db.Dishes.FirstOrDefaultAsync(d => d.Id == id, ct);

    public async Task<int> CreateAsync(Dish dish, CancellationToken ct = default)
    {
        db.Dishes.Add(dish);
        await db.SaveChangesAsync(ct);
        return dish.Id;
    }

    public async Task UpdateAsync(Dish dish, CancellationToken ct = default)
    {
        db.Dishes.Update(dish);
        await db.SaveChangesAsync(ct);
    }

    public async Task DeleteAsync(int id, CancellationToken ct = default)
    {
        var dish = await db.Dishes.FindAsync([id], ct);
        if (dish is null) return;
        db.Dishes.Remove(dish);
        await db.SaveChangesAsync(ct);
    }
}
