package com.ddesign.foyer.dummy;

import android.graphics.drawable.Drawable;

import java.util.Date;

/**
 * Created by Alexandre on 19/11/2015.
 */
public class Item {

    private String id;
    private String id_entity;
    private String content;
    private String details;
    private String price;
    private Drawable drawable;

    public Item(String id, String id_entity, String price, String details, String content, Drawable drawable) {
        this.id = id;
        this.id_entity = id_entity;
        this.price = price;
        this.details = details;
        this.content = content;
        this.drawable = drawable;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getId_entity() {
        return id_entity;
    }

    protected void setId_entity(String id_entity) {
        this.id_entity = id_entity;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public String getDetails() {
        return details;
    }

    public void setDetails(String details) {
        this.details = details;
    }

    public String getPrice() {
        return price;
    }

    public void setPrice(String price) {
        this.price = price;
    }

    public Drawable getDrawable() {
        return drawable;
    }

    public void setDrawable(Drawable drawable) {
        this.drawable = drawable;
    }
}
