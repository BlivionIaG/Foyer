//
//  HomeController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 13/03/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class HomeController: UIViewController, NetworkManagerDelegate {
    
    //Network
    var networkManager = NetworkManager.sharedInstance

    
    @IBOutlet weak var imageTestView: UIImageView!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        networkManager.downloadImage(delegate: self, urlString: "files/mobile/banniere_mobile.jpg")

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

    func didReceiveImage(image: UIImage) {
        dispatch_async(dispatch_get_main_queue()) {
           self.imageTestView.image = image
        }
    }
    
}
