package com.brazzilburger.services.Impl;

import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.List;

import com.brazzilburger.models.Burger;

public interface IBurgerService {
    

    Burger creerBurger(String nom, BigDecimal prix, String imagePath) throws SQLException;
}