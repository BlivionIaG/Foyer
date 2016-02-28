//
//  NetworkManager.swift
//  FoyerISEN
//
//  Created by Renald Morice on 25/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

@objc protocol NetworkManagerDelegate {
    optional func didReceiveResponse(tabData: NSArray)
    optional func didFailToReceiveResponse(strError : String)
}

class NetworkManager: NSObject , NSURLSessionDelegate {
    
    var session : NSURLSession?
    var delegate : NetworkManagerDelegate?
    var authBasicKey : NSData?

    override init(){
        super.init()
        self.session = NSURLSession(configuration: NSURLSessionConfiguration.defaultSessionConfiguration(), delegate:self, delegateQueue:NSOperationQueue.mainQueue())
    }
    
    convenience init(initWithDelegate: NetworkManagerDelegate){
        self.init()
        self.delegate = initWithDelegate
    }
    
    /*------------------------------------------*/
    /* Fonction d'aiguillage pour les requêtes */
    /*----------------------------------------*/
    
    func request(urlString: String, timeoutInterval: Int? = 20, authBasic: Bool? = false, requestType : String, postParams: Dictionary<String, String>? = nil ) {
        
        let request = NSMutableURLRequest(URL: NSURL(string: urlString)!, cachePolicy: NSURLRequestCachePolicy.ReloadIgnoringCacheData, timeoutInterval: NSTimeInterval(Int(timeoutInterval!)) )
        
        //requete GET
        if (requestType == "GET") {
            
            request.HTTPMethod = "GET"
            
        //requete POST
        }else if (requestType == "POST" && postParams != nil) {
            
            request.HTTPMethod = "POST"
            
            do{
                request.HTTPBody = try NSJSONSerialization.dataWithJSONObject(postParams!, options:[] )
                
            } catch let jsonError as NSError {
                self.delegate!.didFailToReceiveResponse!(jsonError.localizedDescription)
            }
            
            request.addValue("application/json", forHTTPHeaderField: "Content-Type")
            request.addValue("application/json", forHTTPHeaderField: "Accept")
            
        //Si les paramètres requestType et/ou postParams ne sont pas bons ...
        } else {
            self.delegate!.didFailToReceiveResponse!("Paramètres requestType et/ou postParams ne sont pas bons !")
            return
        }
        
        //Sécurité Auth Basic
        if (authBasic!) {
            request.setValue("Basic \(self.authBasicKey!)", forHTTPHeaderField: "Authorization")
        }
        
        launchRequest(request)
        
    }
    
    /*-------------------------------------------------------------------------------------------*/
    /* Fonction pour lancer une requête avec récupération des données (ATTENTION : format JSON) */
    /*-----------------------------------------------------------------------------------------*/
    
    func launchRequest(request: NSMutableURLRequest) {
        
        let dataTask = self.session!.dataTaskWithRequest( request, completionHandler: { (data: NSData?, response: NSURLResponse?, error: NSError?) in
            
            if (error != nil) {
                self.delegate!.didFailToReceiveResponse!(error!.localizedDescription)
                
            } else {
                do {
                    
                    if let jsonData = try NSJSONSerialization.JSONObjectWithData(data!, options: NSJSONReadingOptions.MutableContainers) as? NSDictionary {
                        self.delegate!.didReceiveResponse!(NSArray(array: [jsonData]))
                    } else if let jsonData = try NSJSONSerialization.JSONObjectWithData(data!, options: NSJSONReadingOptions.MutableContainers) as? NSArray {
                        self.delegate!.didReceiveResponse!(jsonData)
                    }
                    
                    //let jsonData = try NSJSONSerialization.JSONObjectWithData(data!, options: NSJSONReadingOptions.MutableContainers) as! NSArray
                    //self.delegate!.didReceiveResponse!(jsonData)

                } catch let jsonError as NSError {
                    self.delegate!.didFailToReceiveResponse!(jsonError.localizedDescription)
                }
            }
        })
        dataTask.resume()
    }
    

}