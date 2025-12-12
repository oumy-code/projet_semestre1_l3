package com.brazzilburger.services;



import com.brazzilburger.models.enums.ComplementType;
import com.brazzilburger.services.Impl.IComplementService; // Import de l'interface
import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.List;

import com.brazzilburger.config.CloudinaryConfig;
import com.brazzilburger.models.Complement;

import com.brazzilburger.repositories.ComplementRepository;
import com.brazzilburger.repositories.Impl.IComplementRepository;

public class ComplementService implements IComplementService {

    private final IComplementRepository repository;

    public ComplementService(IComplementRepository repository) {
        this.repository = repository;
    }

    public ComplementService() {
        this(new ComplementRepository());
    }

   
    @Override
    public Complement creerComplement(
            String nom,
            BigDecimal prix,
            String imagePath,
            ComplementType type
    ) throws SQLException {

        String imageUrl = null;

        if (imagePath != null && !imagePath.isEmpty()) {
            imageUrl = CloudinaryConfig.uploadImage(imagePath, "complements");
        }

        Complement complement = new Complement(nom, prix, imageUrl, type);

        repository.create(complement);

        return complement;
    }
}
