package com.ddesign.foyer.HTTP.in;

import android.os.AsyncTask;

import com.ddesign.foyer.item.UpdateListener;

import org.jsoup.Connection;
import org.jsoup.Jsoup;

import java.util.Vector;

/**
 * Created by Alexandre on 22/10/2015.
 */
public class Downloader extends AsyncTask<String, Void, String> {
    protected String url;
    protected String content;
    protected DownloadListener refreshView;
    private Vector<UpdateListener> updateListeners;
    private int id;

    public Downloader(String url){
        this.url = url;
        this.id = -1;
        init();
    }

    public Downloader(String url, int id){
        this.url = url;
        this.id = id;
        init();
    }

    private void init(){
        this.updateListeners = new Vector<UpdateListener>();
        this.content = "";
    }

    public int getId(){
        return this.id;
    }


    public String getContent(){
        return this.content;
    }


    public void update(){
        for(UpdateListener updateListener : updateListeners)
            updateListener.onUpdate(id);
    }

    public void addUpdateListener(UpdateListener updateListener){
        updateListeners.add(updateListener);
    }

    @Override
    protected String doInBackground(String[] params) {
        try {
            System.out.println(url);
            Connection connection =  Jsoup.connect(url).ignoreContentType(true);
            this.content = connection.get().body().ownText().toString();

        } catch (java.io.IOException e){
            System.out.println(e.getMessage().toString());
            return "Download error : " + e.toString();
        }
        return "Download was fine";
    }

    @Override
    protected void onPostExecute(String message) {
        update();
    }
}
