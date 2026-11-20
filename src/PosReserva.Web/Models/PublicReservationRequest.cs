namespace PosReserva.Web.Models;

/// <summary>Wire-compatible with pos-tpv's PublicReservationRequestDto (POST /api/public/reservations).</summary>
public class PublicReservationRequest
{
    public string CustomerName { get; set; } = string.Empty;
    public string? Phone { get; set; }
    public DateTime Date { get; set; }
    public TimeOnly Time { get; set; }
    public int PartySize { get; set; } = 2;
    public int ChildrenCount { get; set; }
    public int HighChairCount { get; set; }
    public string? Comments { get; set; }
}
