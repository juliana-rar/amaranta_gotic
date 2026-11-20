namespace PosReserva.Web.Data;

public class GalleryImage
{
    public int Id { get; set; }
    public string ImageUrl { get; set; } = string.Empty;
    public string Caption { get; set; } = string.Empty;
    public int DisplayOrder { get; set; }
}
