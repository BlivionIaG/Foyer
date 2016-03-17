//
//  HomeController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 13/03/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class HomeController: UIViewController {
    
    //Network
    var networkManager = NetworkManager.sharedInstance

    override func viewDidLoad() {
        super.viewDidLoad()

    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    @IBAction func logoutTouched(sender: AnyObject) {
        networkManager.username = nil
        networkManager.authBasicKey = nil
        //Switch à la page de connexion
        self.performSegueWithIdentifier("Logout", sender: nil)
    }

}
