using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.Net;
using System.IO;

namespace RecipeApp
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
       
        string filters_string="";
        string type;
        public Dictionary<string, string> filters = new Dictionary<string, string>();
        string search_results;
        public MainWindow()
        {
            InitializeComponent();
        }

        private void DoTheThings() 
        {
            string path = "http://collectivecookbook.com/json/" +type+ "/search.php/?";
            foreach (var filt in filters) 
            {
                filters_string += filt.Key+"="+filt.Value+"&";
            }
            filters.Clear();
            path += filters_string;
            path.Replace(" ", "%20");
            Console.WriteLine("\n\n" + path + "\n\n");
            WebRequest req = WebRequest.Create(path);
            filters_string = "";
            WebResponse resp = req.GetResponse();
            
            WebClient client = new WebClient();
            Stream stream = resp.GetResponseStream();
            StreamReader reader = new StreamReader(stream);


            string resultfromdb = reader.ReadToEnd();
            char[] delim = {'\n'};
            string [] dictionaries =  resultfromdb.Split(delim);
            List<Newtonsoft.Json.Linq.JObject> jObjects = new List<Newtonsoft.Json.Linq.JObject>();

            foreach (var dictionary in dictionaries)
            {
                if (dictionary.Length != 0)
                {
                    jObjects.Add(Newtonsoft.Json.Linq.JObject.Parse(dictionary));
                }
            }
            DisplayResult(jObjects);
            stream.Close();
            
        }

        #region magical UI stuff

        #region display results
        private void DisplayResult(List<Newtonsoft.Json.Linq.JObject> listjobj)
        {
            for (int i = 0; i < listjobj.Count(); i++ )
               {
                    search_results += listjobj[i].ToString() + "\n";
                    FormatResult(listjobj[i], i);
               }

            //ResultSearch1.Content = search_results;
            search_results = "";

            filters.Clear();
        }
        private void FormatResult(Newtonsoft.Json.Linq.JObject jobj, int i)
        {
            if(type == "recipes")
            {
                string name = jobj["name"].ToString();
                string author = jobj["author_name"].ToString();
                string instructions = jobj["instructions"].ToString();
                string preptime = jobj["prep_time"].ToString();
                string portions = jobj["portions"].ToString();
                string description = jobj["description"].ToString();
                string rating = jobj["rating"].ToString();
    

                if (i == 0)
                {
                    ResultSearch0.Content = "RECIPE NAME: " + name + "\nAUTHOR: " + author + "\nINSTRUCTIONS: " + instructions + "\nPREP TIME: " + preptime + "\nPORTIONS: " + portions + "\nDESCRIPTION: " + description + "\nRATING: "+rating;
  
                }

                if (i == 1)
                {
                    ResultSearch1.Content = "RECIPE NAME: " + name + "\nAUTHOR: " + author + "\nINSTRUCTIONS: " + instructions + "\nPREP TIME: " + preptime + "\nPORTIONS: " + portions + "\nDESCRIPTION: " + description + "\nRATING: "+rating;
                }

                if (i == 2)
                {
                    ResultSearch2.Content = "RECIPE NAME: " + name + "\nAUTHOR: " + author + "\nINSTRUCTIONS: " + instructions + "\nPREP TIME: " + preptime + "\nPORTIONS: " + portions + "\nDESCRIPTION: " + description + "\nRATING: " + rating;
                }

                if (i == 3)
                {
                    ResultSearch3.Content = "RECIPE NAME: " + name + "\nAUTHOR: " + author + "\nINSTRUCTIONS: " + instructions + "\nPREP TIME: " + preptime + "\nPORTIONS: " + portions + "\nDESCRIPTION: " + description + "\nRATING: " + rating;
                }
            }

            if (type == "cookbooks")
            {
                string name = jobj["name"].ToString();
                string author = jobj["author_name"].ToString();
                string rating = jobj["rating"].ToString();

                if (i == 0)
                {
                    ResultSearch0.Content = "COOKBOOK NAME: " + name + "\nAUTHOR: " + author + "\nRATING: " + rating;
                }

                if (i == 1)
                {
                    ResultSearch1.Content = "COOKBOOK NAME: " + name + "\nAUTHOR: " + author + "\nRATING: " + rating;
                }

                if (i == 2)
                {
                    ResultSearch2.Content = "COOKBOOK NAME: " + name + "\nAUTHOR: " + author  + "\nRATING: " + rating;
                }

                if (i == 3)
                {
                    ResultSearch3.Content = "COOKBOOK NAME: " + name + "\nAUTHOR: " + author + "\nRATING: " + rating;
                }
            }
            
            
            
        }

        #endregion
       

        #region Text Inputs
        private void TextCheckerRecipe()
        {
            if (this.RecipeNameInput.Text != "")
            {
                if (!filters.ContainsKey("rdb_name"))
                {
                    filters.Add("rdb_name", this.RecipeNameInput.Text);
                }
                else
                {
                    filters.Remove("rdb_name");
                    filters.Add("rdb_name", this.RecipeNameInput.Text);
                }
            }
            else 
            {
                filters.Remove("rdb_name");
            }


            if (this.RecipeAuthorInput.Text != "")
            {
                if (!filters.ContainsKey("rdb_author_name"))
                {
                    filters.Add("rdb_author_name", this.RecipeAuthorInput.Text);
                }
                else
                {
                    filters.Remove("rdb_author_name");
                    filters.Add("rdb_author_name", this.RecipeAuthorInput.Text);
                }
            }
            else 
            {
                filters.Remove("rdb_author_name");
            } 
                
          
            if (this.PrepTimeInput.Text !="")
            {
                if (!filters.ContainsKey("prep_time"))
                {
                    filters.Add("prep_time", this.PrepTimeInput.Text);
                }
                else
                {
                    filters.Remove("prep_time");
                    filters.Add("prep_time", this.PrepTimeInput.Text);
                }
            }
            
            if (this.IngredientIncInput.Text != "")
            {
                string[] ingredients = this.IngredientIncInput.Text.Split(", ".ToCharArray());
                for (int i = 0; i < ingredients.Length; i++ )
                {
                    filters.Add("using["+i+"]", ingredients[i]);
                }
            }

            if (this.IngredientExInput.Text != "") 
            {
                 string[] ingredients = this.IngredientExInput.Text.Split(", ".ToCharArray());
                 for (int i = 0; i < ingredients.Length; i++)
                 {
                     filters.Add("using_only[" + i + "]", ingredients[i]);
                 }
            }
        }

        private void TextCheckerCookbook() 
        {
            if (this.CookbookAuthorInput.Text != "")
            {
                if (!filters.ContainsKey("rdb_author_name"))
                {
                    filters.Add("rdb_author_name", this.CookbookAuthorInput.Text);
                }
                else
                {
                    filters.Remove("rdb_author_name");
                    filters.Add("rdb_author_name", this.CookbookAuthorInput.Text);
                }
            }
            else
            {
                filters.Remove("rdb_author_name");
            }

            if (this.CookbookNameInput.Text != "")
            {
                if (!filters.ContainsKey("rdb_name"))
                {
                    filters.Add("rdb_name", this.CookbookNameInput.Text);
                }
                else
                {
                    filters.Remove("rdb_name");
                    filters.Add("rdb_name", this.CookbookNameInput.Text);
                }
            }
            else
            {
                filters.Remove("rdb_name");
            }
        }


        #endregion

        #region search buttons
        private void RecipesSearch_Click(object sender, RoutedEventArgs e)
        {
            RecipesSearch.Foreground = Brushes.Wheat;
            RecipeFields.Visibility = Visibility.Visible;
            RecipeInputs.Visibility = Visibility.Visible;
            StartSearchButton.Visibility = Visibility.Visible;
            CookbooksSearch.Foreground = Brushes.White;
            CookbookFields.Visibility = Visibility.Collapsed;
            CookbookInputs.Visibility = Visibility.Collapsed;
            filters.Clear();
            filters_string = "";
            CookbookNameInput.Text = "";
            CookbookAuthorInput.Text = "";

            TextCheckerRecipe();
            ResultSearch0.Content = "";
            ResultSearch1.Content = "";
            ResultSearch2.Content = "";
            ResultSearch3.Content = "";
            type = "recipes";
           
        }

       
        private void CookbooksSearch_Click(object sender, RoutedEventArgs e)
        {
            RecipesSearch.Foreground = Brushes.White;
            RecipeFields.Visibility = Visibility.Collapsed;
            RecipeInputs.Visibility = Visibility.Collapsed;
            StartSearchButton.Visibility = Visibility.Visible;
            CookbooksSearch.Foreground = Brushes.Wheat;
            CookbookFields.Visibility = Visibility.Visible;
            CookbookInputs.Visibility = Visibility.Visible;
            RecipeAuthorInput.Text = "";
            RecipeNameInput.Text = "";
            PrepTimeInput.Text = "";
            IngredientIncInput.Text = "";
            IngredientExInput.Text = "";
            filters.Clear();
            filters_string = "";

            TextCheckerCookbook();
            ResultSearch0.Content = "";
            ResultSearch1.Content = "";
            ResultSearch2.Content = "";
            ResultSearch3.Content = "";
            type = "cookbooks";
        }
        #endregion

        #region dietary options
        private void VegOption_Click(object sender, RoutedEventArgs e)
        {
            VegOption.Foreground = Brushes.Wheat;
            VegOptionR.Foreground = Brushes.Wheat;
            GlutenOption.Foreground = Brushes.White;
            GlutenOptionR.Foreground = Brushes.White;

            if (!filters.ContainsKey("dietary_restriction"))
                filters.Add("dietary_restriction[]", "vegetarian");

            else 
                {
                    filters.Remove("dietary_restriction[]");
                    filters["dietary_restriction[]"] = "vegetarian";
            }
                
        }
        private void GlutenOption_Click(object sender, RoutedEventArgs e)
        {
            VegOption.Foreground = Brushes.White;
            VegOptionR.Foreground = Brushes.White;
            GlutenOption.Foreground = Brushes.Wheat;
            GlutenOptionR.Foreground = Brushes.Wheat;

            if (!filters.ContainsKey("dietary_restriction"))
                filters.Add("dietary_restriction[]", "gluten_free");

            else
            {
                filters.Remove("dietary_restriction[]");
                filters["dietary_restriction[]"] = "gluten_free";
            }
        }
        #endregion

        #region rate buttons
        private void rate0_Click(object sender, RoutedEventArgs e)
        {
            rate0.Foreground = Brushes.Wheat;
            rate1.Foreground = Brushes.White;
            rate2.Foreground = Brushes.White;
            rate3.Foreground = Brushes.White;
            rate4.Foreground = Brushes.White;
            rate5.Foreground = Brushes.White;

            rate0R.Foreground = Brushes.Wheat;
            rate1R.Foreground = Brushes.White;
            rate2R.Foreground = Brushes.White;
            rate3R.Foreground = Brushes.White;
            rate4R.Foreground = Brushes.White;
            rate5R.Foreground = Brushes.White;

            if (!filters.ContainsKey("rating"))
                filters.Add("rating", "0");

            else
                filters.Remove("rating");
                filters["rating"] = "0";
            
        }
        private void rate1_Click(object sender, RoutedEventArgs e)
        {
            rate0.Foreground = Brushes.Wheat;
            rate1.Foreground = Brushes.Wheat;
            rate2.Foreground = Brushes.White;
            rate3.Foreground = Brushes.White;
            rate4.Foreground = Brushes.White;
            rate5.Foreground = Brushes.White;

            rate0R.Foreground = Brushes.Wheat;
            rate1R.Foreground = Brushes.Wheat;
            rate2R.Foreground = Brushes.White;
            rate3R.Foreground = Brushes.White;
            rate4R.Foreground = Brushes.White;
            rate5R.Foreground = Brushes.White;

            if (!filters.ContainsKey("rating"))
                filters.Add("rating", "1");

            else
                filters.Remove("rating");
                filters["rating"] = "1";
        }
        private void rate2_Click(object sender, RoutedEventArgs e)
        {
            rate0.Foreground = Brushes.Wheat;
            rate1.Foreground = Brushes.Wheat;
            rate2.Foreground = Brushes.Wheat;
            rate3.Foreground = Brushes.White;
            rate4.Foreground = Brushes.White;
            rate5.Foreground = Brushes.White;

            rate0R.Foreground = Brushes.Wheat;
            rate1R.Foreground = Brushes.Wheat;
            rate2R.Foreground = Brushes.Wheat;
            rate3R.Foreground = Brushes.White;
            rate4R.Foreground = Brushes.White;
            rate5R.Foreground = Brushes.White;

            if (!filters.ContainsKey("rating"))
                filters.Add("rating", "2");

            else
                filters.Remove("rating");
                filters["rating"] = "2";
        }
        private void rate3_Click(object sender, RoutedEventArgs e)
        {
            rate0.Foreground = Brushes.Wheat;
            rate1.Foreground = Brushes.Wheat;
            rate2.Foreground = Brushes.Wheat;
            rate3.Foreground = Brushes.Wheat;
            rate4.Foreground = Brushes.White;
            rate5.Foreground = Brushes.White;

            rate0R.Foreground = Brushes.Wheat;
            rate1R.Foreground = Brushes.Wheat;
            rate2R.Foreground = Brushes.Wheat;
            rate3R.Foreground = Brushes.Wheat;
            rate4R.Foreground = Brushes.White;
            rate5R.Foreground = Brushes.White;

            if (!filters.ContainsKey("rating"))
                filters.Add("rating", "3");

            else
                filters.Remove("rating");
                filters["rating"] = "3";
        }
        private void rate4_Click(object sender, RoutedEventArgs e)
        {
            rate0.Foreground = Brushes.Red;
            rate1.Foreground = Brushes.Red;
            rate2.Foreground = Brushes.Red;
            rate3.Foreground = Brushes.Red;
            rate4.Foreground = Brushes.Red;
            rate5.Foreground = Brushes.White;

            rate0R.Foreground = Brushes.Red;
            rate1R.Foreground = Brushes.Red;
            rate2R.Foreground = Brushes.Red;
            rate3R.Foreground = Brushes.Red;
            rate4R.Foreground = Brushes.Red;
            rate5R.Foreground = Brushes.White;

            if (!filters.ContainsKey("rating"))
                filters.Add("rating", "4");

            else
                filters.Remove("rating");
                filters["rating"] = "4";
        }
        private void rate5_Click(object sender, RoutedEventArgs e)
        {
            rate0.Foreground = Brushes.Wheat;
            rate1.Foreground = Brushes.Wheat;
            rate2.Foreground = Brushes.Wheat;
            rate3.Foreground = Brushes.Wheat;
            rate4.Foreground = Brushes.Wheat;
            rate5.Foreground = Brushes.Wheat;

            rate0R.Foreground = Brushes.Wheat;
            rate1R.Foreground = Brushes.Wheat;
            rate2R.Foreground = Brushes.Wheat;
            rate3R.Foreground = Brushes.Wheat;
            rate4R.Foreground = Brushes.Wheat;
            rate5R.Foreground = Brushes.Wheat;

            if (!filters.ContainsKey("rating"))
                filters.Add("rating", "5");

            else
                filters.Remove("rating");
                filters["rating"] = "5";
        }
        #endregion
        private void SearchButton_Click(object sender, RoutedEventArgs e)
        {
            HomeGrid.Visibility = Visibility.Collapsed;
            SearchGrid.Visibility = Visibility.Visible;
        }
        private void HomeButton_Click(object sender, RoutedEventArgs e)
        {
            SearchGrid.Visibility = System.Windows.Visibility.Collapsed;
            HomeGrid.Visibility = System.Windows.Visibility.Visible;
            filters.Clear();
            ResultSearch0.Content = "";
            ResultSearch1.Content = "";
            ResultSearch2.Content = "";
            ResultSearch3.Content = "";
        }
        private void StartSearchButton_Click(object sender, RoutedEventArgs e)
        {
            if (type == "cookbooks")
            {
                TextCheckerCookbook();
            }
            if (type == "recipes")
            {
                TextCheckerRecipe();
            }
            DoTheThings();
            Results.Visibility = Visibility.Visible;
            filters.Clear();
        }

        #endregion

    }
}
