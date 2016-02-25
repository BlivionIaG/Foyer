//
//  NetworkManager.swift
//  FoyerISEN
//
//  Created by Renald Morice on 25/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit


@objc protocol NetworkManagerDelegate {
    optional func didReceiveResponse(info: [ String : AnyObject ])
    optional func didFailToReceiveResponse()
}

class NetworkManager: NSObject , NSURLSessionDelegate {
    
    var session : NSURLSession?
    var delegate : NetworkManagerDelegate?

    override init(){
        super.init()
    }
    
    convenience init(initWithDelegate: NetworkManagerDelegate){
        self.init()
        self.delegate = initWithDelegate
        self.session = NSURLSession(configuration: NSURLSessionConfiguration.defaultSessionConfiguration(), delegate:self, delegateQueue:NSOperationQueue.mainQueue())
    }
    
    func makeRequest(urlString: String, postParams: Dictionary<String, String>) {
        
        let requestUrl = NSURL(string: urlString)
        
        guard requestUrl != nil else {
            print("NetworkManager : requestUrl is nil !")
            return
        }
        
        /*let request = NSMutableURLRequest(URL: requestUrl!, cachePolicy: NSURLRequestCachePolicy.ReloadIgnoringCacheData, timeoutInterval: 15)
        request.HTTPMethod = "POST"
        request.setValue("application/x-www-form-urlencoded", forHTTPHeaderField: "Content-Type")*/
        
        let request = NSMutableURLRequest(URL: requestUrl!)
        request.HTTPMethod = "POST"
        
        do{
            request.HTTPBody = try NSJSONSerialization.dataWithJSONObject(postParams, options:[] )
        } catch let jsonError as NSError {
            // Handle parsing error
            print("JSONError: \(jsonError.localizedDescription)")
        }
        request.addValue("application/json", forHTTPHeaderField: "Content-Type")
        request.addValue("application/json", forHTTPHeaderField: "Accept")
        
        
        let dataTask = self.session!.dataTaskWithRequest( request, completionHandler: { (data: NSData?, response: NSURLResponse?, error: NSError?) in
            
            if let responseError = error {
                self.delegate!.didFailToReceiveResponse!()
                print("Reponse Error: \(responseError )")
                
            } else {
                do {
                    let jsonData = try NSJSONSerialization.JSONObjectWithData(data!, options: NSJSONReadingOptions.MutableContainers) as! [String: AnyObject]
                    self.delegate!.didReceiveResponse!(jsonData)
                    print("Response: \(jsonData)")
                } catch let jsonError as NSError {
                    // Handle parsing error
                    self.delegate!.didFailToReceiveResponse!()
                    print("JSONError: \(jsonError.localizedDescription)")
                }
            }
        })
        dataTask.resume()
    }

}