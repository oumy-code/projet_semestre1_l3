package com.brazzilburger.services.Impl;

import com.brazzilburger.models.Complement;
import com.brazzilburger.models.enums.ComplementType;
import java.math.BigDecimal;
import java.sql.SQLException;
import java.util.List;

public interface IComplementService {
  
    Complement creerComplement( String nom, BigDecimal prix,String imagePath,ComplementType type)throws SQLException;
    List<Complement> listerComplements(boolean disponiblesUniquement) throws SQLException;

}