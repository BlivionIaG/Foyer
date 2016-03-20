//
//  ProductController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 19/03/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class ProductController: UIViewController {
    
    var productManager = ProductManager()

    @IBOutlet weak var menuButton: UIBarButtonItem!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        //Pour le bouton menu
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = "revealToggle:"
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
    }

}
