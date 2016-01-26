package com.ddesign.foyer.HTTP.in;

import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.os.AsyncTask;

import com.nostra13.universalimageloader.core.ImageLoader;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by Alexandre on 22/10/2015.
 */
public final class ImageDownloader extends AsyncTask<String, Void, String> {

    public Map<Integer, String> imagePaths;
    public String url;
    public Map<Integer, Drawable> drawables;
    private ProduitsListDownloader plDownload;

    public ImageDownloader(String url, Map<Integer, String> imagePaths, ProduitsListDownloader plDownloader) {
        this.imagePaths = imagePaths;
        this.url = url;
        this.plDownload = plDownloader;
        this.drawables = new HashMap<Integer, Drawable>();
    }

    @Override
    protected String doInBackground(String[] params) {
        for (Integer id : imagePaths.keySet()){
            System.out.println("Downloading : " + url + imagePaths.get(id));
            ImageLoader imageLoader = ImageLoader.getInstance();
            Bitmap bitmap = imageLoader.loadImageSync(url + imagePaths.get(id));
            Drawable drawable = new BitmapDrawable(bitmap);
            drawables.put(id, drawable);
            System.out.println("Download end");
        }
        return "some message";
    }

    @Override
    protected void onPostExecute(String message) {
        plDownload.update2();
    }
}
