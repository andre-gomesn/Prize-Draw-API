namespace app.Models
{
    public class Logs_Prize
    {
        public int Id { get; set; }
        public int? IdUser  { get; set; }
        // Prize uses Item ID as reference, but cannot be used as foreign key because it can be 0
        // 0 means no prize won
        public required int Prize  { get; set; }
        public DateTime DatePrize { get; set; } = DateTime.Now;
    }
}
