package com.ddesign.foyer.dummy;

import java.util.ArrayList;
import java.util.List;

/**
 * Helper class for providing sample content for user interfaces created by
 * Android template wizards.
 * <p/>
 * TODO: Replace all uses of this class before publishing your app.
 */
public class Content {

    /**
     * An array of sample (dummy) items.
     */
    public static List<Item> PRODUIT_ITEMS = new ArrayList<Item>();
    public static List<Item> COMMAND_ITEMS = new ArrayList<Item>();


    public Content(){
    }

    public List<Item> getProduitItems(){
        return PRODUIT_ITEMS;
    }

    public List<Item> getCommandItems(){
        return COMMAND_ITEMS;
    }

    public static void update(){

    }

    private void addItem(ProduitItem item) {
        PRODUIT_ITEMS.add(item);
    }

    private void addItem(CommandItem item) {
        COMMAND_ITEMS.add(item);
    }


    /**
     * A dummy item representing a piece of content.
     */

}
