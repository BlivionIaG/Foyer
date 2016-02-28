//
//  ViewController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 25/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

//var globales
var networkManager = NetworkManager.init()


class ViewController: UIViewController, NetworkManagerDelegate {

    override func viewDidLoad() {
        super.viewDidLoad()
        
        
        networkManager.delegate = self
        let postParams : [String : String] = ["username":"rmoric18", "password":"moriceisen"]
        networkManager.request("http://foyer.p4ul.tk/api/cas/", requestType: "POST", postParams: postParams)
        //networkManager.request("http://foyer.p4ul.tk/api/product/", requestType: "GET")
        
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func didReceiveResponse(tabData: NSArray) {
        
        for item in tabData {
            
            /*------------------------------------------*/
            /* Interpretation retour connexion au CAS  */
            /*----------------------------------------*/
            
            let data = item as! NSDictionary
            
            let tabStatus : NSDictionary = data["status"] as! NSDictionary
            let keyString : String = tabStatus["key"] as! String
            
            networkManager.authBasicKey = keyString
            
            print(networkManager.authBasicKey!)
            
            /*--------------------------------*/
            /* Interpretation liste produit  */
            /*------------------------------*/
            
        }
        
    } 
    
    func didFailToReceiveResponse(strError : String) {
        print( "UIViewController : \(strError)")
    }


}

