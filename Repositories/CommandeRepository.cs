using Dapper;
using brasilBugerC_.Helpers;
using brasilBugerC_.Models;
using brasilBugerC_.Models.Enums;

namespace brasilBugerC_.Repositories;

public class CommandeRepository : ICommandeRepository
{
    private readonly DbConnectionFactory _connectionFactory;
    private readonly ILigneCommandeRepository _ligneCommandeRepository;

    public CommandeRepository(DbConnectionFactory connectionFactory, ILigneCommandeRepository ligneCommandeRepository)
    {
        _connectionFactory = connectionFactory;
        _ligneCommandeRepository = ligneCommandeRepository;
    }

    public async Task<int> CreateAsync(Commande commande)
    {
        const string sql = @"
            INSERT INTO commande (id_client, id_zone, montant_total, etat, type_recuperation, adresse_livraison)
            VALUES (@IdClient, @IdZone, @MontantTotal, @Etat::etat_commande, @TypeRecuperation::type_recuperation, @AdresseLivraison)
            RETURNING id";

        using var connection = _connectionFactory.CreateConnection();
        
        var id = await connection.ExecuteScalarAsync<int>(sql, new
        {
            commande.IdClient,
            commande.IdZone,
            commande.MontantTotal,
            Etat = commande.Etat.ToString().ToLower().Replace("encours", "en_cours"),
            TypeRecuperation = commande.TypeRecuperation.ToString().ToLower()
                .Replace("surplace", "sur_place")
                .Replace("arecuperer", "a_recuperer"),
            commande.AdresseLivraison
        });

        return id;
    }

    public async Task<IEnumerable<Commande>> GetByClientIdAsync(int clientId)
    {
        const string sql = @"
            SELECT id, id_client, id_gestionnaire, id_zone, id_livreur,
                   date_commande, montant_total, 
                   etat::text as etat, 
                   type_recuperation::text as type_recuperation,
                   adresse_livraison, date_modification
            FROM commande
            WHERE id_client = @ClientId
            ORDER BY date_commande DESC";

        using var connection = _connectionFactory.CreateConnection();
        
        var results = await connection.QueryAsync<dynamic>(sql, new { ClientId = clientId });
        
        var commandes = new List<Commande>();
        
        foreach (var result in results)
        {
            var commande = new Commande
            {
                Id = result.id,
                IdClient = result.id_client,
                IdGestionnaire = result.id_gestionnaire,
                IdZone = result.id_zone,
                IdLivreur = result.id_livreur,
                DateCommande = result.date_commande,
                MontantTotal = result.montant_total,
                Etat = ParseEtat(result.etat),
                TypeRecuperation = ParseTypeRecuperation(result.type_recuperation),
                AdresseLivraison = result.adresse_livraison,
                DateModification = result.date_modification
            };
            
            // Charger les lignes de commande
            commande.LignesCommande = (await _ligneCommandeRepository.GetByCommandeIdAsync(commande.Id)).ToList();
            
            commandes.Add(commande);
        }
        
        return commandes;
    }

    public async Task<Commande?> GetByIdAsync(int id)
    {
        const string sql = @"
            SELECT id, id_client, id_gestionnaire, id_zone, id_livreur,
                   date_commande, montant_total, 
                   etat::text as etat, 
                   type_recuperation::text as type_recuperation,
                   adresse_livraison, date_modification
            FROM commande
            WHERE id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        
        var result = await connection.QueryFirstOrDefaultAsync<dynamic>(sql, new { Id = id });
        
        if (result == null)
            return null;

        return new Commande
        {
            Id = result.id,
            IdClient = result.id_client,
            IdGestionnaire = result.id_gestionnaire,
            IdZone = result.id_zone,
            IdLivreur = result.id_livreur,
            DateCommande = result.date_commande,
            MontantTotal = result.montant_total,
            Etat = ParseEtat(result.etat),
            TypeRecuperation = ParseTypeRecuperation(result.type_recuperation),
            AdresseLivraison = result.adresse_livraison,
            DateModification = result.date_modification
        };
    }

    public async Task<Commande?> GetByIdWithDetailsAsync(int id)
    {
        const string sql = @"
            SELECT 
                c.id, c.id_client, c.id_gestionnaire, c.id_zone, c.id_livreur,
                c.date_commande, c.montant_total, c.etat::text as etat, 
                c.type_recuperation::text as type_recuperation,
                c.adresse_livraison, c.date_modification,
                z.id as zone_id, z.nom as zone_nom, z.prix_livraison as zone_prix_livraison, 
                z.quartiers as zone_quartiers, z.date_creation as zone_date_creation
            FROM commande c
            LEFT JOIN zone z ON c.id_zone = z.id
            WHERE c.id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        
        var result = await connection.QueryFirstOrDefaultAsync<dynamic>(sql, new { Id = id });
        
        if (result == null)
            return null;

        var commande = new Commande
        {
            Id = result.id,
            IdClient = result.id_client,
            IdGestionnaire = result.id_gestionnaire,
            IdZone = result.id_zone,
            IdLivreur = result.id_livreur,
            DateCommande = result.date_commande,
            MontantTotal = result.montant_total,
            Etat = ParseEtat(result.etat),
            TypeRecuperation = ParseTypeRecuperation(result.type_recuperation),
            AdresseLivraison = result.adresse_livraison,
            DateModification = result.date_modification
        };

        // Mapper la zone si elle existe
        if (result.zone_id != null)
        {
            commande.Zone = new Zone
            {
                Id = result.zone_id,
                Nom = result.zone_nom,
                PrixLivraison = result.zone_prix_livraison,
                Quartiers = result.zone_quartiers,
                DateCreation = result.zone_date_creation
            };
        }

        // Charger les lignes de commande
        commande.LignesCommande = (await _ligneCommandeRepository.GetByCommandeIdAsync(commande.Id)).ToList();
        
        return commande;
    }

    public async Task<bool> UpdateAsync(Commande commande)
    {
        const string sql = @"
            UPDATE commande
            SET montant_total = @MontantTotal,
                etat = @Etat::etat_commande,
                date_modification = CURRENT_TIMESTAMP
            WHERE id = @Id";

        using var connection = _connectionFactory.CreateConnection();
        var rowsAffected = await connection.ExecuteAsync(sql, new
        {
            commande.Id,
            commande.MontantTotal,
            Etat = commande.Etat.ToString().ToLower().Replace("encours", "en_cours")
        });
        
        return rowsAffected > 0;
    }

    // ============================================
    // Méthodes helper privées pour parser les enums
    // ============================================
    
    private EtatCommande ParseEtat(string etat)
    {
        return etat?.ToLowerInvariant() switch
        {
            "en_cours" => EtatCommande.EnCours,
            "termine" => EtatCommande.Termine,
            "annule" => EtatCommande.Annule,
            _ => EtatCommande.EnCours
        };
    }

    private TypeRecuperation ParseTypeRecuperation(string type)
    {
        return type?.ToLowerInvariant() switch
        {
            "sur_place" => TypeRecuperation.SurPlace,
            "a_recuperer" => TypeRecuperation.ARecuperer,
            "livraison" => TypeRecuperation.Livraison,
            _ => TypeRecuperation.SurPlace
        };
    }
}