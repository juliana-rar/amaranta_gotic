namespace PosReserva.Web.Data;

public class Testimonial
{
    public int Id { get; set; }
    public string AuthorName { get; set; } = string.Empty;
    public string Quote { get; set; } = string.Empty;
    public int DisplayOrder { get; set; }
}
