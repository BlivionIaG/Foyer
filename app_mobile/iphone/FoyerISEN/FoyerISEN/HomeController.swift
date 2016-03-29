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
    
    //Storyboard Outlets
    @IBOutlet weak var menuButton: UIBarButtonItem!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        //Pour le bouton menu
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:)) //"revealToggle:"
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
}
