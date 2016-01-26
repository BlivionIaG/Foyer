package com.ddesign.foyer.dummy;

import android.graphics.drawable.Drawable;

import java.util.Date;

/**
 * Created by Alexandre on 22/10/2015.
 */
public class ProduitItem extends Item{

    private boolean available;
    private Date date;

    public ProduitItem(String id, String id_entity, String price, String details, String content, Drawable drawable, Date date, boolean available) {
        super(id, id_entity, price, details, content, drawable);
        this.date = date;
        this.available = available;
    }

    public boolean isAvailable(){
        return this.available;
    }

    public Date getDate(){
        return this.date;
    }

    public String getIdProduit(){
        return this.getId_entity();
    }

    public void setIdProduit(String idProduit){
        setId_entity(idProduit);
    }

    @Override
    public String toString() {
            return getContent();
        }
}
