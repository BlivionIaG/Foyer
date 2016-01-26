package com.ddesign.foyer.dummy;

import android.graphics.drawable.Drawable;

/**
 * Created by Alexandre on 22/10/2015.
 */
public class CommandItem extends Item{

    public CommandItem(String id, String id_entity, String price, String details, String content, Drawable drawable) {
        super(id, id_entity, price, details, content, drawable);
    }

    public String getIdCommand(){
            return this.getId_entity();
    }

    public void setIdCommand(String idCommand){
        setId_entity(idCommand);
    }

    @Override
    public String toString() {
            return getContent();
        }

}
