package com.ddesign.foyer.dummy;

import android.graphics.drawable.Drawable;

import java.util.Vector;

/**
 * Created by Alexandre on 22/10/2015.
 */
public class CommandItem extends Item{

    private String date, time, period_start, period_end, state;
    private Vector<ProduitItem> produitItems;


    public CommandItem(String id, String id_entity, String price, String details, String content,
                       Drawable drawable) {
        super(id, id_entity, price, details, content, drawable);
    }

    public void setSecondary(String date, String time, String period_start, String period_end,
                             String state,
                             Vector<ProduitItem> produitItems){
        this.produitItems = produitItems;
        this.date = date;
        this.time = time;
        this.state = state;
        this.period_start = period_start;
        this.period_end = period_end;
    }

    public Vector<ProduitItem> getProduitItems(){
        return this.produitItems;
    }

    @Override
    public String toString() {
            return "commande #" + getId_entity();
    }

}
