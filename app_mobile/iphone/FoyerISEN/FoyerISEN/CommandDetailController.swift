//
//  CommandDetailController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 29/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class CommandDetailController: UIViewController {
    
    /*----------  VARIABLES  ----------*/
    var command: Command?
    
    @IBOutlet weak var numCommandLabel: UILabel!
    @IBOutlet weak var datePeriodeCommandLabel: UILabel!
    @IBOutlet weak var totalCommandLabel: UILabel!
    @IBOutlet weak var stateCommandLabel: UILabel!
    /*---------------------------------*/
    
    //Au chargement de la vue
    //-----------------------
    override func viewDidLoad() {
        super.viewDidLoad()
        
        let dateFormatter = NSDateFormatter()
        dateFormatter.dateFormat = "dd-MM-yyyy"
        
        self.numCommandLabel.text = "Commande n° \(String(self.command!.id_command))"
        
        self.totalCommandLabel.text = "\(String(self.command!.total)) €"
        
        self.datePeriodeCommandLabel.text = "\(dateFormatter.stringFromDate(self.command!.date)) : \(self.command!.periode_debut) - \(self.command!.periode_fin)"
        
        self.stateCommandLabel.text = "Etat : \(self.command!.state)"
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

}
