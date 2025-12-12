package com.brazzilburger.repositories.Impl;



import java.sql.SQLException;
import java.util.List;

import com.brazzilburger.models.Burger;

public interface IBurgerRepository {
    Burger create(Burger burger) throws SQLException;
    List<Burger> findAll() throws SQLException;
    List<Burger> findAllNonArchived() throws SQLException;
    Burger findById(Integer id) throws SQLException;
    void update(Burger burger) throws SQLException;
    void archive(Integer id) throws SQLException;
    void unarchive(Integer id) throws SQLException;
}
