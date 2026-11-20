using System.Net;
using System.Net.Http.Json;
using PosReserva.Web.Models;

namespace PosReserva.Web.Services;

public record ReservationResult(bool Success, string? ErrorMessage)
{
    public static ReservationResult Ok() => new(true, null);
    public static ReservationResult Fail(string message) => new(false, message);
}

/// <summary>
/// Server-to-server client for pos-tpv's public reservation intake endpoint. The customer's
/// browser never talks to pos-tpv directly — only this server-side client does, holding the
/// shared API key from configuration (never sent to the browser).
/// </summary>
public class ReservationApiClient(HttpClient http)
{
    public async Task<ReservationResult> CreateAsync(PublicReservationRequest request, CancellationToken ct = default)
    {
        HttpResponseMessage response;
        try
        {
            response = await http.PostAsJsonAsync("/api/public/reservations", request, ct);
        }
        catch (HttpRequestException)
        {
            return ReservationResult.Fail("No se pudo contactar con el restaurante. Inténtalo de nuevo más tarde o llámanos por teléfono.");
        }

        if (response.IsSuccessStatusCode)
            return ReservationResult.Ok();

        if (response.StatusCode == HttpStatusCode.TooManyRequests)
            return ReservationResult.Fail("Se han recibido demasiadas solicitudes. Inténtalo de nuevo en unos minutos.");

        // pos-tpv's validation text is English (its own convention) and not meant to be shown
        // verbatim to a Spanish-speaking customer, so a rejected request always gets a generic,
        // stable Spanish message here instead of relaying the server's raw wording.
        return ReservationResult.Fail(
            "No se pudo completar la reserva. Revisa la fecha (no puede ser pasada), el número de comensales y tus datos de contacto, e inténtalo de nuevo.");
    }
}
