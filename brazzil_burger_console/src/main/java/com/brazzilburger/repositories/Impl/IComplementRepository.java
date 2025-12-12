package com.brazzilburger.repositories.Impl;


import java.sql.SQLException;
import java.util.List;

import com.brazzilburger.models.Complement;

public interface IComplementRepository {
    Complement create(Complement complement) throws SQLException;
    List<Complement> findAll() throws SQLException;
    List<Complement> findAllNonArchived() throws SQLException;
    Complement findById(Integer id) throws SQLException;
    void update(Complement complement) throws SQLException;
    void archive(Integer id) throws SQLException;
    void unarchive(Integer id) throws SQLException;
}
