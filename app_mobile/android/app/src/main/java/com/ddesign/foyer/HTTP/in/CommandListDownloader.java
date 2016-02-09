package com.ddesign.foyer.HTTP.in;

import android.graphics.drawable.Drawable;
import android.os.AsyncTask;
import android.text.Html;

import com.ddesign.foyer.dummy.Cart;
import com.ddesign.foyer.dummy.CommandItem;
import com.ddesign.foyer.dummy.Content;
import com.ddesign.foyer.dummy.Item;
import com.ddesign.foyer.dummy.ProduitItem;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import org.jsoup.Connection;
import org.jsoup.Jsoup;

import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Vector;

/**
 * Created by Alexandre on 07/02/2016.
 */
public class CommandListDownloader extends AsyncTask<String, Void, String> {

    private Vector<DownloadListener> listeners;
    private String url, content;
    private JSONParser parser;
    private int creatorType;

    public static final int CREATOR_CASUAL = 0;
    public static final int CREATOR_CART = 1;


    public CommandListDownloader(String url, String login, int creatorType){
        this.url = url+login;
        this.content = "";
        this.listeners = new Vector<DownloadListener>();
        this.parser = new JSONParser();
        this.creatorType = creatorType;
    }

    @Override
    protected String doInBackground(String... params) {
        // telecharger le json

        try {
            Connection connection =  Jsoup.connect(url).ignoreContentType(true);
            content = connection.get().body().ownText().toString();
        } catch (IOException e) {
            e.printStackTrace();
        }

        return null;
    }

    @Override
    protected void onPostExecute(String message) {
        parseCommand(content);
        if(creatorType == CREATOR_CART)
            Cart.onDownload();
    }

    private void parseCommand(String content){
        try{
            JSONArray jsonArray = (JSONArray) parser.parse(content);
            if(jsonArray.size() > 0) {
                int id = 0;
                if(Content.COMMAND_ITEMS.size()>0)
                    Content.COMMAND_ITEMS.clear();

                for (Object object : jsonArray) {
                    JSONObject jsonObject = (JSONObject) object;
                    String id_command = (String) jsonObject.get("id_commande");
                    String id_entity = "" + id;
                    String price = "" + jsonObject.get("total");
                    String details = "";
                    String period_start = (String) jsonObject.get("periode_debut");
                    String period_end = (String) jsonObject.get("periode_fin");
                    String date = (String) jsonObject.get("date");
                    String time = (String) jsonObject.get("time");
                    String state = (String) jsonObject.get("state");
                    Vector<ProduitItem> produitItems = new Vector<ProduitItem>();
                    for (Object product : (JSONArray) parser.parse(jsonObject.get("product").toString())){
                        JSONObject jsonProduct = (JSONObject) product;
                        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                        Date date_p = formatter.parse((String)jsonProduct.get("date"));
                        produitItems.add(new ProduitItem((String) jsonProduct.get("id_produit"),
                                (String) jsonProduct.get("id_produit"),
                                (String) jsonProduct.get("price"),
                                (String) jsonProduct.get("description"),
                                (String) jsonProduct.get("name"),
                                null,
                                date_p,
                                Boolean.parseBoolean((String) jsonProduct.get("available"))));
                    }
                    String content_c = "commande #" + id_command;
                    Drawable drawable = null;
                    CommandItem commandItem = new CommandItem(id_entity, id_command, price, details, content_c, drawable);
                    commandItem.setSecondary(date, time, period_start, period_end, state, produitItems);
                    Content.COMMAND_ITEMS.add(commandItem);
                    id++;
                }

            }
        }
        catch(ParseException pe){
            System.out.println(pe);
        } catch (java.text.ParseException e) {
            e.printStackTrace();
        }
    }
}
