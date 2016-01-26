package com.ddesign.foyer.HTTP.in;

import com.ddesign.foyer.dummy.ProduitItem;
import com.ddesign.foyer.dummy.Content;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Alexandre on 22/10/2015.
 */
public class ProduitsListDownloader extends Downloader{

    private ImageDownloader imageDownloader;
    private String url_image;
    private JSONParser parser;

    public ProduitsListDownloader(String url, String url_image, DownloadListener refreshView){
        super(url);
        this.url_image = url_image;
        this.refreshView = refreshView;
        this.parser = new JSONParser();
    }

    @Override
    public void update(){

        System.out.println("content : " + content);

        try{
            JSONArray jsonArray = (JSONArray) parser.parse(content);
            if(jsonArray.size() > 0) {
                Map<Integer, String> imagePaths = new HashMap<Integer, String>();
                for (Object object : jsonArray) {
                    JSONObject jsonObject = (JSONObject) object;
                    imagePaths.put(Integer.parseInt((String) jsonObject.get("id_product")),(String) jsonObject.get("image"));
                }
                if(url_image.length()>0)
                    this.imageDownloader = new ImageDownloader(url_image,imagePaths,this);
                imageDownloader.execute();
            }
        }
        catch(ParseException pe){
            System.out.println(pe);
        }
    }

    public void update2(){
        System.out.println("update 2");

        try{
            JSONArray jsonArray = (JSONArray) parser.parse(content);
            if(jsonArray.size() > 0) {
                int id = 0;
                for (Object object : jsonArray) {
                    JSONObject jo = (JSONObject) object;
                    String id_product = (String) jo.get("id_product");
                    String name = (String) jo.get("name");
                    String price = (String) jo.get("price");
                    String details = (String) jo.get("description");
                    boolean available = jo.get("available").equals("1");
                    SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd");
                    Date date = formatter.parse((String)jo.get("date"));

                    ProduitItem product = new ProduitItem(Integer.toString(id), id_product,
                            price, details, name,
                            imageDownloader.drawables.get(Integer.parseInt(id_product)),
                            date, available);
                    Content.PRODUIT_ITEMS.add(product);
                    id++;
                }
                refreshView.updateView();
            }
        }
        catch(ParseException pe){
            System.out.println(pe);
        } catch (java.text.ParseException e) {
            System.out.println("[date]" + e);
        }
    }

}
