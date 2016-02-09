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
    public static List<Item> PRODUCT_ITEMS = new ArrayList<Item>();
    public static List<Item> COMMAND_ITEMS = new ArrayList<Item>();
    public static int SELECTED = 0;

    /*
     * FLAGS
     */
    public static int PRODUCT_SELECTED = 0;
    public static int COMMAND_SELECTED = 1;

    public Content(){
    }

    public List<Item> getProductItems(){
        return PRODUCT_ITEMS;
    }

    public List<Item> getCommandItems(){
        return COMMAND_ITEMS;
    }

    public static void update(){

    }

    private void addItem(ProduitItem item) {
        PRODUCT_ITEMS.add(item);
    }

    private void addItem(CommandItem item) {
        COMMAND_ITEMS.add(item);
    }


    /**
     * A dummy item representing a piece of content.
     */

}
