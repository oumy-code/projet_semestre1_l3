// wwwroot/js/site.js
// Scripts JavaScript pour Brasil Burger

// Fermer automatiquement les alertes après 5 secondes
document.addEventListener('DOMContentLoaded', function() {
    // Auto-close alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Confirmer avant de supprimer un article du panier
    const deleteButtons = document.querySelectorAll('button[type="submit"]');
    deleteButtons.forEach(button => {
        if (button.textContent.includes('✕')) {
            button.addEventListener('click', function(e) {
                if (!confirm('Voulez-vous vraiment retirer cet article du panier ?')) {
                    e.preventDefault();
                }
            });
        }
    });

    // Afficher/masquer les détails de livraison
    const livraisonRadio = document.getElementById('livraison');
    if (livraisonRadio) {
        const livraisonDetails = document.getElementById('livraisonDetails');
        
        // Vérifier l'état initial
        const allRadios = document.querySelectorAll('input[name="TypeRecuperation"]');
        allRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'Livraison') {
                    livraisonDetails.style.display = 'block';
                } else {
                    livraisonDetails.style.display = 'none';
                }
            });
        });
    }

    // Calculer et afficher le total dans le checkout
    const zoneSelect = document.getElementById('ZoneId');
    if (zoneSelect) {
        zoneSelect.addEventListener('change', function() {
            // Cette fonctionnalité peut être étendue pour afficher
            // dynamiquement les frais de livraison
            console.log('Zone sélectionnée:', this.value);
        });
    }

    // Animation smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Fonction pour mettre à jour la quantité dans le panier
function updateQuantite(form) {
    const quantite = form.querySelector('input[name="quantite"]').value;
    if (quantite < 0) {
        alert('La quantité ne peut pas être négative');
        return false;
    }
    return true;
}

// Fonction pour valider le formulaire de checkout
function validateCheckout() {
    const typeRecuperation = document.querySelector('input[name="TypeRecuperation"]:checked');
    
    if (!typeRecuperation) {
        alert('Veuillez sélectionner un type de récupération');
        return false;
    }

    if (typeRecuperation.value === 'Livraison') {
        const zone = document.getElementById('ZoneId').value;
        const adresse = document.getElementById('AdresseLivraison').value;

        if (!zone) {
            alert('Veuillez sélectionner une zone de livraison');
            return false;
        }

        if (!adresse || adresse.trim() === '') {
            alert('Veuillez saisir une adresse de livraison');
            return false;
        }
    }

    const modePaiement = document.querySelector('input[name="ModePaiement"]:checked');
    if (!modePaiement) {
        alert('Veuillez sélectionner un mode de paiement');
        return false;
    }

    return true;
}

// Log pour debugging
console.log('Brasil Burger - Site JS loaded');