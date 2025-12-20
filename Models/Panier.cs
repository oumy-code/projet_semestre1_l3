namespace brasilBugerC_.Models
{
    public class Panier
    {
        public List<PanierItem> Items { get; set; } = new List<PanierItem>();
    }

    public class PanierItem
    {
        public int ProduitId { get; set; }
        public string Nom { get; set; } = "";
        public decimal Prix { get; set; }
        public int Quantite { get; set; }
    }
}
