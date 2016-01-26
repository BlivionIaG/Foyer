package com.ddesign.foyer.dummy;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.support.design.widget.FloatingActionButton;
import android.view.LayoutInflater;
import android.view.View;
import android.view.Window;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;

import com.ddesign.foyer.HTTP.out.Requester;
import com.ddesign.foyer.R;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;

import org.json.JSONException;
import org.json.JSONObject;
import org.json.simple.JSONArray;
import org.json.simple.parser.JSONParser;
import org.jsoup.Jsoup;

import java.io.IOException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Vector;


/**
 * Created by Alexandre on 26/10/2015.
 */
public class Cart {

    public static HashMap<ProduitItem,Integer> produits = new HashMap<ProduitItem,Integer>();

    public void Cart(){

    }

    public static void addItem(ProduitItem item){
        if(produits.containsKey(item))
            produits.put(item, produits.get(item)+1);
        else
            produits.put(item, 1);
    }

    public static void retItem(ProduitItem item){
        if(produits.containsKey(item)){
            if(produits.get(item)==1){
                produits.remove(item);
            }else {
                produits.put(item, produits.get(item) - 1);
            }
        }
    }

    public static void initCartFab(final Activity context){
        final Dialog dialog = new Dialog(context);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.cart_dialog);
        final LinearLayout itemList = (LinearLayout) dialog.findViewById(R.id.item_list);
        final Button button_val = (Button) dialog.findViewById(R.id.button_val);
        Button button_clo = (Button) dialog.findViewById(R.id.button_close);

        button_val.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                checkout(context);
            }
        });
        button_clo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
            }
        });

        final LayoutInflater inflater = LayoutInflater.from(context);
        final TextView text_empty_cart = (TextView) inflater.inflate(R.layout.textview_empty_cart, null);

        FloatingActionButton fab_cart = (FloatingActionButton) context.findViewById(R.id.fab_cart);
        fab_cart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                itemList.removeAllViewsInLayout();

                Vector<View> items = Cart.resumeDialog(context, dialog);
                for(View item : items) {
                    itemList.addView(item);
                }
                if(items.isEmpty()) {
                    button_val.setVisibility(View.GONE);
                    itemList.addView(text_empty_cart);
                }else {
                    button_val.setVisibility(View.VISIBLE);
                }
                dialog.show();

            }
        });
    }

    public static Vector<View> resumeDialog(final Context context,final Dialog dialog){

        Vector<View> cartItems = new Vector<View>();
        LayoutInflater inflater = LayoutInflater.from(context);

        final LinearLayout totalLayout = (LinearLayout) dialog.findViewById(R.id.total_layout);
        final String devise = context.getString(R.string.devise);

        for(Map.Entry<ProduitItem,Integer> entry : produits.entrySet()) {
            final ProduitItem item = entry.getKey();
            int quantity = entry.getValue();

            RelativeLayout rl = (RelativeLayout) inflater.inflate(R.layout.cart_item, null);

            final TextView quantityText = (TextView)rl.findViewById(R.id.quantity);
            quantityText.setText(quantity + "");

            ((TextView) rl.findViewById(R.id.name)).setText(item.getContent());
            final TextView priceText =  (TextView)rl.findViewById(R.id.price_value);
            priceText.setText(" " +
                    Float.parseFloat(item.getPrice())+ context.getString(R.string.devise));
            Button button_add = (Button) rl.findViewById(R.id.button_add);
            Button button_ret = (Button) rl.findViewById(R.id.button_ret);
            button_add.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    addItem(item);
                    int quantity = produits.get(item);
                    quantityText.setText(quantity+"");
                    priceText.setText(" " + item.getPrice() + devise);
                    calcTotal(totalLayout, devise);
                }
            });
            button_ret.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    retItem(item);
                    if(produits.containsKey(item)){
                        int quantity = produits.get(item);
                        quantityText.setText(quantity+"");
                        priceText.setText(" " + item.getPrice() + devise);
                    }else{
                        quantityText.setText("0");
                    }

                    calcTotal(totalLayout, devise);
                }
            });
            cartItems.add(rl);
        }

        calcTotal(totalLayout, devise);

        return cartItems;
    }

    private static void calcTotal(LinearLayout ll, String devise) {
        TextView totalText = (TextView) ll.findViewById(R.id.total_text);
        if (produits.size() > 0) {
            ll.setVisibility(View.VISIBLE);
            float total = 0;
            for(Map.Entry<ProduitItem,Integer> entry : produits.entrySet()) {
                final ProduitItem item = entry.getKey();
                int quantity = entry.getValue();
                total += quantity * Float.parseFloat(item.getPrice());
            }
            totalText.setText(" " + total + devise);
        } else {
            ll.setVisibility(View.GONE);
        }
    }

    private static void checkout(final Context context){
        final Dialog dialog = new Dialog(context);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.checkout_dialog);
        Button buttonVal = (Button) dialog.findViewById(R.id.button_checkout);
        Button buttonCan = (Button) dialog.findViewById(R.id.button_cancel);
        final Spinner spinner = (Spinner) dialog.findViewById(R.id.date_spinner);

        DateFormat hourFormat = new SimpleDateFormat("HH");
        DateFormat minuteFormat = new SimpleDateFormat("mm");

        Date date = new Date();
        int hour = Integer.parseInt(hourFormat.format(date));
        int minute = Integer.parseInt(minuteFormat.format(date));

        final String period_start = formatDate(hour,minute);

        minute = ((int) Math.ceil(((float) minute)/((float) 10))) * 10;
        if(minute>=60){
            minute = 0;
            hour+=1;
        }
        List<String> list = new ArrayList<String>();

        while(hour!=24 || (hour==23 && minute > 50)){
            list.add(formatDate(hour,minute));
            minute+=10;
            if(minute>=60){
                minute = 0;
                hour+=1;
            }
        }
        ArrayAdapter<String> adapter =new ArrayAdapter<String>(context,
                android.R.layout.simple_list_item_1,list);
        spinner.setAdapter(adapter);

        buttonVal.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String selection = (String) spinner.getSelectedItem();
                command(period_start, selection, context);
            }
        });

        buttonCan.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
            }
        });

        dialog.show();
    }

    private static String formatDate(int hour,int minute){
        String inter = "h";
        String start = "";
        if(hour<10)
            start = "0";
        if(minute<10)
            inter = "h0";
        return start+hour+inter+minute;
    }

    private static void command(String period_start, String period_end, Context context){


        try {
            JSONObject message = new JSONObject();
            message.put("state",""+1);
            message.put("login", "anicol17");
            message.put("periode_debut", period_start);
            message.put("periode_fin", period_end);
            message.put("date", "01-01-0222");
            JSONArray products = new JSONArray();
            for(Map.Entry<ProduitItem,Integer> entry : produits.entrySet()) {
                ProduitItem item = entry.getKey();
                int quantity = entry.getValue();
                JSONObject product = new JSONObject();
                product.put("id_product",item.getIdProduit());
                product.put("quantity",quantity);
                products.add(product);
            }
            message.put("product", products);

            JsonParser jsonParser = new JsonParser();
            System.out.println(message.toString());
            System.out.println(jsonParser.parse(message.toString()).getAsJsonObject().toString());
            Ion.with(context)
                    .load("http://p4ul.tk/Foyer/api/command/")
                    .setJsonObjectBody(jsonParser.parse(message.toString()).getAsJsonObject())
                    .asJsonObject()
                    .setCallback(new FutureCallback<JsonObject>() {
                        @Override
                        public void onCompleted(Exception e, JsonObject result) {
                           System.out.println(result.toString());
                        }
                    });
            ((Requester) new Requester("http://p4ul.tk/Foyer/api/command/",message)).execute();

        } catch (JSONException e) {
            e.printStackTrace();
        }
    }
}
