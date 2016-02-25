//
//  ViewController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 25/02/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ViewController: UIViewController, NetworkManagerDelegate {

    override func viewDidLoad() {
        super.viewDidLoad()
        
        
        let networkManager = NetworkManager(initWithDelegate: self)
        let postParams : [String : String] = ["username":"rmoric18", "password":"moriceisen"]
        networkManager.makeRequest("http://foyer.p4ul.tk/api/cas/?username=rmoric18?password=moriceisen", postParams: postParams)
        
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func didReceiveResponse(info: [ String : AnyObject ]) {
        // Lire les données retournées par la requête
        let username = info[ "username" ]
        print("Name: \(username)")
        let key = info[ "key" ]
        print("Name: \(key)")
    } 
    
    func didFailToreceiveResponse() {
        print( "Il y a eu une erreur !" )
    }


}

