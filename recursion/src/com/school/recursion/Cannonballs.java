package com.school.recursion;

import javax.swing.*;
import java.awt.event.*;

public class Cannonballs {
    private JFrame     window;
    private JLabel     prompt, result;
    private JTextField entry;
    private JButton    calc;
    private int        count, height, total;
    
    /**
     * Constructor for the class Cannonballs
     * <p>
     * This class calculates the number of cannonballs in a pyramid of a given 
     * height. 
     * 
     * @param  win  the user interface window
     */
    public Cannonballs(JFrame win)
    {
        window = win;
        prompt = new JLabel("Enter the height of the pyramid:");
        entry  = new JTextField();
        calc   = new JButton("Calculate");
        result = new JLabel();

        window.getContentPane().removeAll();
        
        prompt.setVerticalAlignment(JLabel.TOP);
        prompt.setSize(400, 100);
        prompt.setLocation(10, 5);
        
        window.add(prompt);
        
        entry.setSize(50, 20);
        entry.setLocation(190, 5);
        
        window.add(entry);
        
        calc.setSize(100, 25);
        calc.setLocation(75, 30);
        calc.addActionListener(calcAction());
        
        window.add(calc);
        
        window.repaint();
    }
    
    private ActionListener calcAction()
    {
        ActionListener action = new ActionListener() {
            public void actionPerformed(ActionEvent event) {
                try {                    
                    height = Integer.parseInt(entry.getText());
                    
                    if (height < 0) 
                        throw new NumberFormatException();
                    
                    count = 0;
                    total = 0;
                    
                    calcTotal();
                    
                    addResult("There are " + total + 
                                " cannonballs in the pyramid.");
                } catch(NumberFormatException e) {
                    addResult("Invalid entry.  Try again.");
                }
            }
        };
        
        return action;
    }
    
    private void addResult(String s)
    {
        window.remove(result);
        
        result.setVerticalAlignment(JLabel.TOP);
        result.setSize(400, 100);
        result.setLocation(10, 65);
        result.setText(s);
        
        window.add(result);
        
        window.repaint();
    }
    
    private void calcTotal()
    {
            
        if (height > 0) {
            height --;
            count  ++;
            total += count * count;
            calcTotal();
        }
    }
}