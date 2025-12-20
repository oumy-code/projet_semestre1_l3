using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;

namespace brasilBugerC_.Repositories;

public class PaiementRepository : IPaiementRepository
{
    private readonly DbConnectionFactory _connectionFactory;

    public PaiementRepository(DbConnectionFactory connectionFactory)
    {
        _connectionFactory = connectionFactory;
    }

    public async Task<int> CreateAsync(Paiement paiement)
    {
        const string sql = @"
            INSERT INTO paiement (id_commande, montant, mode_paiement, statut)
            VALUES (@IdCommande, @Montant, @ModePaiement::mode_paiement, @Statut::statut_paiement)
            RETURNING id";

        using var connection = _connectionFactory.CreateConnection();
        
        var id = await connection.ExecuteScalarAsync<int>(sql, new
        {
            paiement.IdCommande,
            paiement.Montant,
            ModePaiement = paiement.ModePaiement.ToString().ToLower(),
            Statut = paiement.Statut.ToString().ToLower().Replace("enattente", "en_attente")
        });

        return id;
    }

    public async Task<Paiement?> GetByCommandeIdAsync(int commandeId)
    {
        const string sql = @"
            SELECT id, id_commande, date_paiement, montant, 
                   mode_paiement::text as mode_paiement, 
                   statut::text as statut
            FROM paiement
            WHERE id_commande = @CommandeId";

        using var connection = _connectionFactory.CreateConnection();
        
        var result = await connection.QueryFirstOrDefaultAsync<dynamic>(sql, new { CommandeId = commandeId });
        
        if (result == null)
            return null;

        return new Paiement
        {
            Id = result.id,
            IdCommande = result.id_commande,
            DatePaiement = result.date_paiement,
            Montant = result.montant,
            ModePaiement = ParseModePaiement(result.mode_paiement),
            Statut = ParseStatutPaiement(result.statut)
        };
    }

    public async Task<bool> UpdateStatutAsync(int id, string statut)
    {
        const string sql = @"
            UPDATE paiement
            SET statut = @Statut::statut_paiement
            WHERE id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        var rowsAffected = await connection.ExecuteAsync(sql, new { Id = id, Statut = statut });
        return rowsAffected > 0;
    }

    // ============================================
    // Méthodes helper privées pour parser les enums
    // ============================================
    
    private ModePaiement ParseModePaiement(string mode)
    {
        return mode?.ToLowerInvariant() switch
        {
            "wave" => ModePaiement.Wave,
            "om" => ModePaiement.OM,
            _ => ModePaiement.Wave
        };
    }

    private StatutPaiement ParseStatutPaiement(string statut)
    {
        return statut?.ToLowerInvariant() switch
        {
            "en_attente" => StatutPaiement.EnAttente,
            "confirme" => StatutPaiement.Confirme,
            "refuse" => StatutPaiement.Refuse,
            _ => StatutPaiement.EnAttente
        };
    }
}