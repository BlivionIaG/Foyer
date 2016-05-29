//
//  CommandTableViewController.swift
//  FoyerISEN
//
//  Created by Renald Morice on 29/05/2016.
//  Copyright © 2016 Digital Design. All rights reserved.
//

import UIKit

class CommandTableViewController: UITableViewController {
    
    /*----------  VARIABLES  ----------*/
    @IBOutlet weak var menuButton: UIBarButtonItem!

    /*--------------------------------*/
    
    //Au chargement de la vue
    //-----------------------
    override func viewDidLoad() {
        super.viewDidLoad()
        
        //Pour le bouton menu
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        notificationsCenter.addObserver(self, selector: #selector(commandDownloaded), name: MyNotifications.commandDownloaded, object: nil)
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    @IBAction func refreshButtonPressed(sender: AnyObject) {
        commandManager.loadCommands()
        self.tableView.reloadData()
    }
    
    //Nombre de cellules
    //------------------
    override func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return commandManager.commands.count
    }
    
    // Configuration de chaque cellule
    //--------------------------------
    override func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        
        let cell = tableView.dequeueReusableCellWithIdentifier("CommandCell") as! CommandCell
        cell.setCommandCell( commandManager.commands[indexPath.row] )
        
        return cell
    }
    
    //Changement d'écran vers l'écran de détail de la commande
    //--------------------------------------------------------
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        
        if let identifier = segue.identifier {
            
            switch identifier {
                
            case "CommandDetailSegue":
                let commandDetailVC = segue.destinationViewController as! CommandDetailController
                if let indexPath = self.tableView.indexPathForCell(sender as! CommandCell) {
                    commandDetailVC.command = commandManager.commands[indexPath.row]
                }
                
            default : break
            }
        }
        
    }

    
    
    
    //Quand les commandes sont téléchargées
    //-------------------------------------
    func commandDownloaded(){
        self.tableView.reloadData()
    }
    
    
    deinit{
        notificationsCenter.removeObserver(self)
    }
    
}
