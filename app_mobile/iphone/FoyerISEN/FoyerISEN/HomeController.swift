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
    
    //Storyboard Outlets
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var banniereImageView: UIImageView!
    @IBOutlet weak var welcomeLabel: UILabel!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        //Pour le bouton menu
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:)) //"revealToggle:"
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        self.welcomeLabel.text = "Bienvenue \(networkManager.username!)"
        
        networkManager.request(delegate: self, urlString: "banniere/", requestType: "GET")
        
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func didReceiveData(response: String, tabData: NSArray) {
        print(tabData.description)
        
        for item in tabData {
            
            if let strUrlBanniere : String = item["url"] as? String {
                networkManager.downloadImage(delegate: self, urlString: "files/mobile/"+strUrlBanniere)
            } else {
                print("erreur urlBanniere")
            }
            
        }
        
    }
    
    func didReceiveImage(image: UIImage) {
        self.banniereImageView.image = image
    }
    
    
    func didFailToReceiveResponse(strError: String) {
        print(strError)
    }
    
}
