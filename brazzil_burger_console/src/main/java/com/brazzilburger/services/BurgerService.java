package com.brazzilburger.services;



import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.List;

import com.brazzilburger.config.CloudinaryConfig;
import com.brazzilburger.models.Burger;
import com.brazzilburger.repositories.BurgerRepository;
import com.brazzilburger.repositories.Impl.IBurgerRepository;
import com.brazzilburger.services.Impl.IBurgerService; // Import de l'interface


public class BurgerService implements IBurgerService {

    private final IBurgerRepository repository;

    public BurgerService(IBurgerRepository repository) {
        this.repository = repository;
    }

    public BurgerService() {
        this(new BurgerRepository());
    }


    @Override
    public Burger creerBurger(String nom, BigDecimal prix, String imagePath) throws SQLException {
        String imageUrl = null;
        if (imagePath != null && !imagePath.isEmpty()) {
            imageUrl = CloudinaryConfig.uploadImage(imagePath, "burgers");
        }

        Burger burger = new Burger(nom, prix, imageUrl);
        repository.create(burger);
        return burger;
    }
    @Override
    public List<Burger> listerBurgers(boolean disponiblesUniquement) throws SQLException {
        return disponiblesUniquement ? repository.findAllNonArchived() : repository.findAll();
    }

   

}