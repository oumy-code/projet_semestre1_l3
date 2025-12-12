package com.brazzilburger.repositories.Impl;





import java.sql.SQLException;
import java.util.List;

import com.brazzilburger.models.CompositionMenu;
import com.brazzilburger.models.Menu;

public interface IMenuRepository {

    Menu create(Menu menu) throws SQLException;

    void addComposition(Integer idMenu, Integer idBurger, Integer idComplement, int quantite) throws SQLException;

    List<Menu> findAll() throws SQLException;

    List<Menu> findAllNonArchived() throws SQLException;

    Menu findById(Integer id) throws SQLException;

    List<CompositionMenu> findCompositions(Integer idMenu) throws SQLException;

    void update(Menu menu) throws SQLException;

    void archive(Integer id) throws SQLException;

    void deleteComposition(Integer idComposition) throws SQLException;
}
