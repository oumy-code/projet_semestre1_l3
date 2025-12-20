// Helpers/DapperTypeHandlers.cs
using Dapper;
using brasilBugerC_.Models.Enums;
using System.Data;

namespace brasilBugerC_.Helpers;

/// <summary>
/// Type Handler pour convertir les enums PostgreSQL en enums C#
/// </summary>
public class EtatCommandeTypeHandler : SqlMapper.TypeHandler<EtatCommande>
{
    public override EtatCommande Parse(object value)
    {
        if (value == null || value is DBNull)
            return EtatCommande.EnCours;

        var stringValue = value.ToString()?.ToLowerInvariant();

        return stringValue switch
        {
            "en_cours" => EtatCommande.EnCours,
            "termine" => EtatCommande.Termine,
            "annule" => EtatCommande.Annule,
            _ => EtatCommande.EnCours
        };
    }

    public override void SetValue(IDbDataParameter parameter, EtatCommande value)
    {
        parameter.Value = value switch
        {
            EtatCommande.EnCours => "en_cours",
            EtatCommande.Termine => "termine",
            EtatCommande.Annule => "annule",
            _ => "en_cours"
        };
    }
}

public class TypeRecuperationTypeHandler : SqlMapper.TypeHandler<TypeRecuperation>
{
    public override TypeRecuperation Parse(object value)
    {
        if (value == null || value is DBNull)
            return TypeRecuperation.SurPlace;

        var stringValue = value.ToString()?.ToLowerInvariant();

        return stringValue switch
        {
            "sur_place" => TypeRecuperation.SurPlace,
            "a_recuperer" => TypeRecuperation.ARecuperer,
            "livraison" => TypeRecuperation.Livraison,
            _ => TypeRecuperation.SurPlace
        };
    }

    public override void SetValue(IDbDataParameter parameter, TypeRecuperation value)
    {
        parameter.Value = value switch
        {
            TypeRecuperation.SurPlace => "sur_place",
            TypeRecuperation.ARecuperer => "a_recuperer",
            TypeRecuperation.Livraison => "livraison",
            _ => "sur_place"
        };
    }
}

public class ModePaiementTypeHandler : SqlMapper.TypeHandler<ModePaiement>
{
    public override ModePaiement Parse(object value)
    {
        if (value == null || value is DBNull)
            return ModePaiement.Wave;

        var stringValue = value.ToString()?.ToLowerInvariant();

        return stringValue switch
        {
            "wave" => ModePaiement.Wave,
            "om" => ModePaiement.OM,
            _ => ModePaiement.Wave
        };
    }

    public override void SetValue(IDbDataParameter parameter, ModePaiement value)
    {
        parameter.Value = value switch
        {
            ModePaiement.Wave => "wave",
            ModePaiement.OM => "om",
            _ => "wave"
        };
    }
}

public class StatutPaiementTypeHandler : SqlMapper.TypeHandler<StatutPaiement>
{
    public override StatutPaiement Parse(object value)
    {
        if (value == null || value is DBNull)
            return StatutPaiement.EnAttente;

        var stringValue = value.ToString()?.ToLowerInvariant();

        return stringValue switch
        {
            "en_attente" => StatutPaiement.EnAttente,
            "confirme" => StatutPaiement.Confirme,
            "refuse" => StatutPaiement.Refuse,
            _ => StatutPaiement.EnAttente
        };
    }

    public override void SetValue(IDbDataParameter parameter, StatutPaiement value)
    {
        parameter.Value = value switch
        {
            StatutPaiement.EnAttente => "en_attente",
            StatutPaiement.Confirme => "confirme",
            StatutPaiement.Refuse => "refuse",
            _ => "en_attente"
        };
    }
}

public class ComplementTypeTypeHandler : SqlMapper.TypeHandler<ComplementType>
{
    public override ComplementType Parse(object value)
    {
        if (value == null || value is DBNull)
            return ComplementType.FRITES;

        var stringValue = value.ToString()?.ToUpperInvariant();

        return stringValue switch
        {
            "FRITES" => ComplementType.FRITES,
            "BOISSON" => ComplementType.BOISSON,
            _ => ComplementType.FRITES
        };
    }

    public override void SetValue(IDbDataParameter parameter, ComplementType value)
    {
        parameter.Value = value switch
        {
            ComplementType.FRITES => "FRITES",
            ComplementType.BOISSON => "BOISSON",
            _ => "FRITES"
        };
    }
}

/// <summary>
/// Classe statique pour enregistrer tous les Type Handlers
/// </summary>
public static class DapperTypeHandlerConfig
{
    private static bool _initialized = false;

    public static void Initialize()
    {
        if (_initialized)
            return;

        // Enregistrer tous les Type Handlers
        SqlMapper.AddTypeHandler(new EtatCommandeTypeHandler());
        SqlMapper.AddTypeHandler(new TypeRecuperationTypeHandler());
        SqlMapper.AddTypeHandler(new ModePaiementTypeHandler());
        SqlMapper.AddTypeHandler(new StatutPaiementTypeHandler());
        SqlMapper.AddTypeHandler(new ComplementTypeTypeHandler());

        _initialized = true;
    }
}