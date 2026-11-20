namespace PosReserva.Web.Data;

/// <summary>One fixed row per day of the week (DayOrder 0=Lunes .. 6=Domingo) — always exactly
/// 7 rows, seeded once at startup, never created/deleted from the admin panel, only edited.</summary>
public class BusinessHour
{
    public int Id { get; set; }
    public int DayOrder { get; set; }
    public bool IsOpen { get; set; }
    public TimeOnly? Shift1Start { get; set; }
    public TimeOnly? Shift1End { get; set; }
    public TimeOnly? Shift2Start { get; set; }
    public TimeOnly? Shift2End { get; set; }
}
