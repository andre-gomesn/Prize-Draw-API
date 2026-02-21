namespace app.Models
{
    public class Prize_Control
    {
        public int Id { get; set; }
        public required string NameControl  { get; set; }
        public string ValueControl { get; set; } = string.Empty;
        public DateTime? DateUpdate { get; set; } = DateTime.Now;
    }
}
