namespace PosReserva.Web.Data;

public class Dish
{
    public int Id { get; set; }
    public string Title { get; set; } = string.Empty;
    public string Description { get; set; } = string.Empty;
    public string ImageUrl { get; set; } = string.Empty;
    public string Category { get; set; } = string.Empty;
    public string PriceText { get; set; } = string.Empty;
    public bool ShowOnHome { get; set; } = false;
    public int DisplayOrder { get; set; }
}
