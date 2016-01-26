package com.ddesign.foyer.HTTP.out;

import android.os.AsyncTask;
import android.os.SystemClock;

import org.apache.http.Header;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.StringEntity;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;
import org.json.JSONObject;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;


/**
 * Created by Alexandre on 31/12/2015.
 */
public class Requester extends AsyncTask<String, Void, String> {

    private String url;
    private JSONObject object;

    public Requester(String url, JSONObject object){
        this.url = url;
        this.object = object;
    }

    @Override
    protected String doInBackground(String... params) {

        return null;
    }
}
