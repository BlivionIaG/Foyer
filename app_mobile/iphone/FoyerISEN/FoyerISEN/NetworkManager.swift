//
//  NetworkManager.swift
//  FoyerISEN
//
//  Created by Renald Morice on 25/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

@objc protocol NetworkManagerDelegate {
    optional func didReceiveData(response : String, tabData: NSArray)
    optional func didFailToReceiveResponse(strError : String)
    optional func didReceiveImage(image: UIImage?)
}

class NetworkManager: NSObject , NSURLSessionDelegate {
    
    var session : NSURLSession?
    var username : String?
    var authBasicKey : String?
    
    let basedUrl = "http://isenclub.fr/foyer/api/"
    
    //class variable : SINGLETON
    class var sharedInstance: NetworkManager {
        struct Singleton {
            static let instance = NetworkManager()
        }
        return Singleton.instance
    }

    override init(){
        super.init()
        self.session = NSURLSession(configuration: NSURLSessionConfiguration.defaultSessionConfiguration(), delegate:self, delegateQueue:NSOperationQueue.mainQueue())
    }
    
    /*------------------------------------------*/
    /* Fonction d'aiguillage pour les requêtes */
    /*----------------------------------------*/
    
    func request(delegate delegate : NetworkManagerDelegate, urlString: String, timeoutInterval: Int? = 20, authBasic: Bool? = false, requestType : String, postParams: [String : String]? = nil ) {
        
        let request = NSMutableURLRequest(URL: NSURL(string: basedUrl + urlString)!, cachePolicy: NSURLRequestCachePolicy.ReloadIgnoringCacheData, timeoutInterval: NSTimeInterval(Int(timeoutInterval!)) )
        
        //requete GET
        if (requestType == "GET") {
            
            request.HTTPMethod = "GET"
            
        //requete POST
        }else if (requestType == "POST" && postParams != nil) {
            
            request.HTTPMethod = "POST"
            request.addValue("application/x-www-form-urlencoded", forHTTPHeaderField:"Content-Type")
            request.setValue("application/json", forHTTPHeaderField: "Accept")
            
            var body : String = ""
            
            if let unWrappedpostParams = postParams {
                var firstTime = true
                
                for (key, value) in unWrappedpostParams {
                    
                    if firstTime{
                        firstTime = false
                    } else {
                        body = body.stringByAppendingString("&")
                    }
                    body = body.stringByAppendingString(key)
                    body = body.stringByAppendingString("=")
                    body = body.stringByAppendingString(value)
                }
            }
            
            request.HTTPBody = body.dataUsingEncoding(NSUTF8StringEncoding)
            
            //Ancienne méthode
            //----------------
            /*do{
                request.HTTPBody = try NSJSONSerialization.dataWithJSONObject(postParams!, options:[] )
                
            } catch let jsonError as NSError {
                delegate.didFailToReceiveResponse!(jsonError.localizedDescription)
            }
            
            request.addValue("application/json", forHTTPHeaderField: "Content-Type")
            equest.addValue("application/json", forHTTPHeaderField: "Accept")*/
            
        } else {
            delegate.didFailToReceiveResponse!("Paramètres requestType et/ou postParams ne sont pas bons !")
            return
        }
        
        //Sécurité Auth Basic
        if let keyAuthBasic = self.authBasicKey {
            request.setValue("Basic " + keyAuthBasic, forHTTPHeaderField: "Authorization")
        }
        
        launchRequest(delegate: delegate, request: request)
        
    }
    
    /*-------------------------------------------------------------------------------------------*/
    /* Fonction pour lancer une requête avec récupération des données (ATTENTION : format JSON) */
    /*-----------------------------------------------------------------------------------------*/
    
    func launchRequest(delegate delegate : NetworkManagerDelegate, request: NSMutableURLRequest) {
        
        let dataTask = self.session!.dataTaskWithRequest( request, completionHandler: { (data: NSData?, response: NSURLResponse?, error: NSError?) in
            
            if (error != nil) {
                delegate.didFailToReceiveResponse!(error!.localizedDescription)
                
            } else {
                do {
                    
                    if let jsonData = try NSJSONSerialization.JSONObjectWithData(data!, options: NSJSONReadingOptions.MutableContainers) as? NSDictionary {
                        delegate.didReceiveData!(response!.description, tabData: NSArray(array: [jsonData]))
                    } else if let jsonData = try NSJSONSerialization.JSONObjectWithData(data!, options: NSJSONReadingOptions.MutableContainers) as? NSArray {
                        delegate.didReceiveData!(response!.description, tabData:jsonData)
                    }

                } catch let jsonError as NSError {
                    print(response.debugDescription)
                    delegate.didFailToReceiveResponse!(jsonError.localizedDescription)
                }
            }
        })
        
        dataTask.resume()
    }
    
    func downloadImage(delegate delegate: NetworkManagerDelegate, urlString: String) {
        
        let urlRequest = NSURLRequest(URL: NSURL(string: basedUrl + urlString)!)
        
        let downloadTask = self.session!.downloadTaskWithRequest(urlRequest, completionHandler: { (url: NSURL?, response: NSURLResponse?, error: NSError?) in
            
            let data = NSData(contentsOfURL: url!)
            let image = UIImage(data: data!)
            
            delegate.didReceiveImage!(image!)
        })
        
        downloadTask.resume()
        
    }
    
    


}